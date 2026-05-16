"""
Unit tests for detect_status_transition() function in market_scraper.py.
Tests the status transition detection logic for the auto-settlement system.
"""

import unittest
from unittest.mock import patch, MagicMock
from datetime import datetime

from market_scraper import detect_status_transition


class TestDetectStatusTransition(unittest.TestCase):
    """Tests for detect_status_transition() function."""

    @patch("market_scraper.get_db_connection")
    def test_returns_old_status_on_waiting_to_open_declared(self, mock_get_conn):
        """When stored status is 'waiting' and new is 'open_declared', returns 'waiting'."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "result_status": "waiting",
            "market_name": "Test Market"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        result = detect_status_transition("test-market", "open_declared", "2025-01-01")
        self.assertEqual(result, "waiting")

    @patch("market_scraper.get_db_connection")
    def test_returns_old_status_on_open_declared_to_closed(self, mock_get_conn):
        """When stored status is 'open_declared' and new is 'closed', returns 'open_declared'."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "result_status": "open_declared",
            "market_name": "Test Market"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        result = detect_status_transition("test-market", "closed", "2025-01-01")
        self.assertEqual(result, "open_declared")

    @patch("market_scraper.get_db_connection")
    def test_returns_none_when_no_change(self, mock_get_conn):
        """When stored status equals new status, returns None (no transition)."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "result_status": "waiting",
            "market_name": "Test Market"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        result = detect_status_transition("test-market", "waiting", "2025-01-01")
        self.assertIsNone(result)

    @patch("market_scraper.get_db_connection")
    def test_returns_none_when_no_existing_record(self, mock_get_conn):
        """When no record exists in DB for the market/date, returns None."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = None
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        result = detect_status_transition("new-market", "waiting", "2025-01-01")
        self.assertIsNone(result)

    @patch("market_scraper.get_db_connection")
    def test_returns_none_on_database_error(self, mock_get_conn):
        """When a database error occurs, returns None (no transition detected)."""
        import mysql.connector
        mock_get_conn.side_effect = mysql.connector.Error("Connection failed")

        result = detect_status_transition("test-market", "open_declared", "2025-01-01")
        self.assertIsNone(result)

    @patch("market_scraper.get_db_connection")
    def test_returns_old_status_on_waiting_to_closed(self, mock_get_conn):
        """When stored status is 'waiting' and new is 'closed', returns 'waiting'."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "result_status": "waiting",
            "market_name": "Test Market"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        result = detect_status_transition("test-market", "closed", "2025-01-01")
        self.assertEqual(result, "waiting")

    @patch("market_scraper.get_db_connection")
    @patch("market_scraper.log")
    def test_logs_transition_when_status_changes(self, mock_log, mock_get_conn):
        """When a transition is detected, it should log the transition details."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "result_status": "waiting",
            "market_name": "Kalyan Day"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        detect_status_transition("kalyan-day", "open_declared", "2025-01-01")

        # Verify log.info was called with transition details
        mock_log.info.assert_called()
        log_message = mock_log.info.call_args[0][0]
        self.assertIn("STATUS TRANSITION", log_message)
        self.assertIn("Kalyan Day", log_message)
        self.assertIn("waiting", log_message)
        self.assertIn("open_declared", log_message)

    @patch("market_scraper.get_db_connection")
    @patch("market_scraper.log")
    def test_does_not_log_when_no_change(self, mock_log, mock_get_conn):
        """When no transition is detected, it should NOT log any transition."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "result_status": "open_declared",
            "market_name": "Test Market"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        detect_status_transition("test-market", "open_declared", "2025-01-01")

        # log.info should NOT have been called (no transition)
        mock_log.info.assert_not_called()

    @patch("market_scraper.get_db_connection")
    def test_queries_correct_market_slug_and_date(self, mock_get_conn):
        """Verify the function queries the DB with the correct market_slug and date."""
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = None
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        detect_status_transition("milan-day", "open_declared", "2025-06-15")

        # Verify the SQL query was called with correct parameters
        mock_cursor.execute.assert_called_once()
        call_args = mock_cursor.execute.call_args
        self.assertIn("market_slug", call_args[0][0])
        self.assertEqual(call_args[0][1], ("milan-day", "2025-06-15"))


class TestUpdateDatabaseIntegration(unittest.TestCase):
    """Tests for update_database() integration with transition detection and settlement triggering."""

    @patch("market_scraper.trigger_settlement")
    @patch("market_scraper.detect_status_transition")
    @patch("market_scraper.get_db_connection")
    def test_calls_detect_transition_before_db_update(self, mock_get_conn, mock_detect, mock_trigger):
        """Transition detection is called BEFORE the DB update for each market."""
        from market_scraper import update_database

        mock_detect.return_value = None  # No transition
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "id": 1, "market_name": "Test", "market_slug": "test",
            "result_status": "waiting", "open_time": "10:00 AM",
            "close_time": "12:00 PM"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        markets = [{
            "market_name": "Test Market",
            "market_slug": "test-market",
            "display_order": 1,
            "open_time": "10:00 AM",
            "close_time": "12:00 PM",
            "open_panna": "345",
            "open_ank": "2",
            "close_panna": "",
            "close_ank": "",
            "jodi": "",
            "full_result": "345-2",
            "status": "open_declared",
        }]

        update_database(markets)

        # detect_status_transition should have been called
        mock_detect.assert_called_once_with("test-market", "open_declared", unittest.mock.ANY)

    @patch("market_scraper.trigger_settlement")
    @patch("market_scraper.detect_status_transition")
    @patch("market_scraper.get_db_connection")
    def test_triggers_open_settlement_on_waiting_to_open_declared(self, mock_get_conn, mock_detect, mock_trigger):
        """When transition is waiting->open_declared, triggers open settlement AFTER DB commit."""
        from market_scraper import update_database

        mock_detect.return_value = "waiting"  # Transition from waiting
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "id": 42, "market_name": "Kalyan Day", "market_slug": "kalyan-day",
            "result_status": "waiting", "open_time": "10:00 AM",
            "close_time": "12:00 PM"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        markets = [{
            "market_name": "Kalyan Day",
            "market_slug": "kalyan-day",
            "display_order": 1,
            "open_time": "10:00 AM",
            "close_time": "12:00 PM",
            "open_panna": "345",
            "open_ank": "2",
            "close_panna": "",
            "close_ank": "",
            "jodi": "",
            "full_result": "345-2",
            "status": "open_declared",
        }]

        update_database(markets)

        # trigger_settlement should be called with 'open' type
        mock_trigger.assert_called_once_with(42, "open", "Kalyan Day")

    @patch("market_scraper.trigger_settlement")
    @patch("market_scraper.detect_status_transition")
    @patch("market_scraper.get_db_connection")
    def test_triggers_close_settlement_on_open_declared_to_closed(self, mock_get_conn, mock_detect, mock_trigger):
        """When transition is open_declared->closed, triggers close settlement AFTER DB commit."""
        from market_scraper import update_database

        mock_detect.return_value = "open_declared"  # Transition from open_declared
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "id": 7, "market_name": "Milan Day", "market_slug": "milan-day",
            "result_status": "open_declared", "open_time": "10:00 AM",
            "close_time": "12:00 PM"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        markets = [{
            "market_name": "Milan Day",
            "market_slug": "milan-day",
            "display_order": 1,
            "open_time": "10:00 AM",
            "close_time": "12:00 PM",
            "open_panna": "289",
            "open_ank": "9",
            "close_panna": "278",
            "close_ank": "7",
            "jodi": "97",
            "full_result": "289-97-278",
            "status": "closed",
        }]

        update_database(markets)

        # trigger_settlement should be called with 'close' type
        mock_trigger.assert_called_once_with(7, "close", "Milan Day")

    @patch("market_scraper.trigger_settlement")
    @patch("market_scraper.detect_status_transition")
    @patch("market_scraper.get_db_connection")
    def test_no_settlement_triggered_when_no_transition(self, mock_get_conn, mock_detect, mock_trigger):
        """When no transition is detected, no settlement is triggered."""
        from market_scraper import update_database

        mock_detect.return_value = None  # No transition
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "id": 1, "market_name": "Test", "market_slug": "test",
            "result_status": "waiting", "open_time": "10:00 AM",
            "close_time": "12:00 PM"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        markets = [{
            "market_name": "Test Market",
            "market_slug": "test-market",
            "display_order": 1,
            "open_time": "10:00 AM",
            "close_time": "12:00 PM",
            "open_panna": "",
            "open_ank": "",
            "close_panna": "",
            "close_ank": "",
            "jodi": "",
            "full_result": "",
            "status": "waiting",
        }]

        update_database(markets)

        # trigger_settlement should NOT be called
        mock_trigger.assert_not_called()

    @patch("market_scraper.trigger_settlement")
    @patch("market_scraper.detect_status_transition")
    @patch("market_scraper.get_db_connection")
    def test_no_settlement_for_non_standard_transitions(self, mock_get_conn, mock_detect, mock_trigger):
        """Transitions like waiting->closed do NOT trigger settlement (only valid pairs do)."""
        from market_scraper import update_database

        # waiting->closed is a detected transition but NOT a valid settlement trigger
        mock_detect.return_value = "waiting"
        mock_cursor = MagicMock()
        mock_cursor.fetchone.return_value = {
            "id": 5, "market_name": "Test", "market_slug": "test",
            "result_status": "waiting", "open_time": "10:00 AM",
            "close_time": "12:00 PM"
        }
        mock_conn = MagicMock()
        mock_conn.cursor.return_value = mock_cursor
        mock_get_conn.return_value = mock_conn

        markets = [{
            "market_name": "Test Market",
            "market_slug": "test-market",
            "display_order": 1,
            "open_time": "10:00 AM",
            "close_time": "12:00 PM",
            "open_panna": "289",
            "open_ank": "9",
            "close_panna": "278",
            "close_ank": "7",
            "jodi": "97",
            "full_result": "289-97-278",
            "status": "closed",
        }]

        update_database(markets)

        # waiting->closed is NOT a valid settlement trigger pair
        mock_trigger.assert_not_called()


if __name__ == "__main__":
    unittest.main()
