-- MainMatka Database Schema
-- Auto-generated from code analysis

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL DEFAULT '',
    `username` VARCHAR(50) NOT NULL DEFAULT '',
    `mobile` VARCHAR(20) NOT NULL DEFAULT '',
    `password` VARCHAR(255) NOT NULL DEFAULT '',
    `mpin` VARCHAR(10) DEFAULT NULL,
    `balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `status` TINYINT NOT NULL DEFAULT 1,
    `role` VARCHAR(20) NOT NULL DEFAULT 'user',
    `date_created` DATE DEFAULT NULL,
    `refer_id` VARCHAR(20) DEFAULT NULL,
    `refer_by` VARCHAR(20) DEFAULT NULL,
    `package_name` VARCHAR(50) DEFAULT NULL,
    `api_access_token` VARCHAR(100) DEFAULT NULL,
    `device_token` VARCHAR(255) DEFAULT NULL,
    `device_id` VARCHAR(255) DEFAULT NULL,
    `device_info` VARCHAR(255) DEFAULT NULL,
    `account_holder_name` VARCHAR(100) DEFAULT NULL,
    `account_number` VARCHAR(50) DEFAULT NULL,
    `ifsc` VARCHAR(20) DEFAULT NULL,
    `bank_name` VARCHAR(100) DEFAULT NULL,
    `total_deposit` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `total_withdrawal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `last_bid_placed_on` DATE DEFAULT NULL,
    INDEX `idx_mobile` (`mobile`),
    INDEX `idx_username` (`username`),
    INDEX `idx_status` (`status`),
    INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Parent games (markets)
CREATE TABLE IF NOT EXISTS `parent_games` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `open_time` TIME DEFAULT NULL,
    `close_time` TIME DEFAULT NULL,
    `result_open_time` TIME DEFAULT NULL,
    `result_close_time` TIME DEFAULT NULL,
    `open_days` VARCHAR(100) DEFAULT 'mon,tue,wed,thu,fri,sat',
    `child_open_id` INT DEFAULT NULL,
    `child_close_id` INT DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1,
    `order_of_display` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Child games (open/close for each parent)
CREATE TABLE IF NOT EXISTS `games` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `parent_game` INT DEFAULT NULL,
    `type` VARCHAR(10) NOT NULL DEFAULT 'open',
    `lottery_time` TIME DEFAULT NULL,
    `result_time` TIME DEFAULT NULL,
    `open_days` VARCHAR(100) DEFAULT 'mon,tue,wed,thu,fri,sat',
    `late_night` TINYINT NOT NULL DEFAULT 0,
    `status` TINYINT NOT NULL DEFAULT 1,
    INDEX `idx_parent_game` (`parent_game`),
    INDEX `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Game results
CREATE TABLE IF NOT EXISTS `result` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `game_id` INT NOT NULL,
    `game_type` VARCHAR(30) NOT NULL DEFAULT 'single_patti',
    `digit` VARCHAR(20) NOT NULL DEFAULT '',
    `date` DATE NOT NULL,
    `time` VARCHAR(20) DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1,
    INDEX `idx_game_date` (`game_id`, `date`),
    INDEX `idx_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Starline games
CREATE TABLE IF NOT EXISTS `starline` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL DEFAULT '',
    `time` TIME DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Starline results
CREATE TABLE IF NOT EXISTS `starline_result` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `game_id` INT NOT NULL,
    `game_type` VARCHAR(30) NOT NULL DEFAULT 'single_patti',
    `digit` VARCHAR(20) NOT NULL DEFAULT '',
    `date` DATE NOT NULL,
    `time` VARCHAR(20) DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1,
    INDEX `idx_game_date` (`game_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Starline chart
CREATE TABLE IF NOT EXISTS `starline_chart` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date` DATE NOT NULL,
    `data` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Jackpot games
CREATE TABLE IF NOT EXISTS `jackpot_games` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `lottery_time` TIME DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Jackpot results
CREATE TABLE IF NOT EXISTS `jackpot_result` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `game_id` INT NOT NULL,
    `game_type` VARCHAR(30) NOT NULL DEFAULT 'jodi',
    `digit` VARCHAR(20) NOT NULL DEFAULT '',
    `date` DATE NOT NULL,
    `time` VARCHAR(20) DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1,
    INDEX `idx_game_date` (`game_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Jackpot rates
CREATE TABLE IF NOT EXISTS `jackpot_rate` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) DEFAULT NULL,
    `rate` DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User transactions (bets, wins, deposits, withdrawals)
CREATE TABLE IF NOT EXISTS `user_transaction` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `game_id` VARCHAR(20) NOT NULL DEFAULT '',
    `game_type` VARCHAR(30) NOT NULL DEFAULT '',
    `digit` VARCHAR(30) NOT NULL DEFAULT '',
    `date` DATE NOT NULL,
    `time` VARCHAR(20) DEFAULT NULL,
    `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `type` VARCHAR(30) NOT NULL DEFAULT '',
    `debit_credit` VARCHAR(10) NOT NULL DEFAULT '',
    `balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `status` TINYINT NOT NULL DEFAULT 0,
    `title` VARCHAR(100) DEFAULT NULL,
    `api_response` TEXT DEFAULT NULL,
    `starline` TINYINT NOT NULL DEFAULT 0,
    `win` VARCHAR(20) DEFAULT NULL,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_date` (`date`),
    INDEX `idx_type` (`type`),
    INDEX `idx_game_id` (`game_id`),
    INDEX `idx_user_date_type` (`user_id`, `date`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Game rates
CREATE TABLE IF NOT EXISTS `game_rate` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) DEFAULT NULL,
    `rate` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `srate` DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Settings
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `value` TEXT DEFAULT NULL,
    UNIQUE KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Single Patti numbers
CREATE TABLE IF NOT EXISTS `sp` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `num` VARCHAR(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Double Patti numbers
CREATE TABLE IF NOT EXISTS `dp` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `num` VARCHAR(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Triple Patti numbers
CREATE TABLE IF NOT EXISTS `tp` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `num` VARCHAR(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Blocked devices
CREATE TABLE IF NOT EXISTS `blocked_devices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `device_id` VARCHAR(255) NOT NULL,
    INDEX `idx_device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notifications
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `message` TEXT DEFAULT NULL,
    `date` DATE DEFAULT NULL,
    `time` VARCHAR(20) DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- SEED DATA
-- =============================================

-- Game rates (Single, Jodi, Single Patti, Double Patti, Triple Patti, Half Sangam, Full Sangam)
INSERT INTO `game_rate` (`id`, `name`, `rate`, `srate`) VALUES
(1, 'Single', 9.00, 10.00),
(2, 'Jodi', 95.00, 0.00),
(3, 'Single Patti', 140.00, 160.00),
(4, 'Double Patti', 280.00, 300.00),
(5, 'Triple Patti', 600.00, 1000.00),
(6, 'Half Sangam', 1000.00, 0.00),
(7, 'Full Sangam', 10000.00, 0.00);

-- Jackpot rates
INSERT INTO `jackpot_rate` (`id`, `name`, `rate`) VALUES
(1, 'Open', 10.00),
(2, 'Close', 10.00),
(3, 'Jodi', 100.00);

-- Default settings
INSERT INTO `settings` (`name`, `value`) VALUES
('app_notice', 'Welcome to MainMatka! Play responsibly.'),
('PWA_whatsapp1', '919999999999'),
('PWA_whatsapp2', '919999999999'),
('deposit_upi_id', ''),
('deposit_qr_url', ''),
('deposit_payee_name', 'MainMatka');

-- Single Patti numbers (SP)
INSERT INTO `sp` (`num`) VALUES
('127'),('136'),('145'),('190'),('235'),('280'),('346'),('390'),('479'),('560'),
('128'),('137'),('146'),('236'),('245'),('290'),('347'),('380'),('470'),('569'),
('129'),('138'),('147'),('156'),('237'),('246'),('345'),('390'),('489'),('570'),
('120'),('139'),('148'),('157'),('238'),('247'),('256'),('346'),('490'),('580'),
('130'),('149'),('158'),('167'),('239'),('248'),('257'),('347'),('356'),('590'),
('140'),('159'),('168'),('230'),('249'),('258'),('267'),('348'),('357'),('456'),
('123'),('150'),('169'),('178'),('240'),('259'),('268'),('349'),('358'),('367'),
('124'),('160'),('179'),('250'),('269'),('278'),('340'),('359'),('368'),('467'),
('125'),('134'),('170'),('189'),('260'),('279'),('350'),('369'),('378'),('468'),
('126'),('135'),('180'),('234'),('270'),('289'),('360'),('379'),('450'),('469');

-- Double Patti numbers (DP)
INSERT INTO `dp` (`num`) VALUES
('118'),('226'),('244'),('299'),('334'),('488'),('550'),('668'),('677'),('100'),
('119'),('155'),('227'),('335'),('344'),('399'),('489'),('669'),('778'),('200'),
('110'),('228'),('255'),('336'),('499'),('660'),('688'),('779'),('300'),('166'),
('112'),('220'),('266'),('338'),('446'),('455'),('699'),('770'),('400'),('788'),
('113'),('122'),('177'),('339'),('366'),('447'),('500'),('799'),('889'),('556'),
('114'),('277'),('330'),('448'),('466'),('556'),('600'),('880'),('899'),('223'),
('115'),('133'),('188'),('223'),('377'),('449'),('557'),('566'),('700'),('990'),
('116'),('224'),('233'),('288'),('440'),('477'),('558'),('800'),('667'),('990'),
('117'),('144'),('199'),('225'),('388'),('559'),('577'),('667'),('900'),('990'),
('126'),('135'),('180'),('234'),('270'),('289'),('360'),('379'),('450'),('469');

-- Triple Patti numbers (TP)
INSERT INTO `tp` (`num`) VALUES
('000'),('111'),('222'),('333'),('444'),('555'),('666'),('777'),('888'),('999');

-- Sample parent game (TEMP TEST MARKET for testing)
INSERT INTO `parent_games` (`name`, `open_time`, `close_time`, `result_open_time`, `result_close_time`, `open_days`, `status`, `order_of_display`) VALUES
('TEMP TEST MARKET', '00:00:00', '23:59:00', '00:05:00', '23:59:00', 'mon,tue,wed,thu,fri,sat,sun', 1, 99);

-- Create child games for the test market
SET @test_parent_id = LAST_INSERT_ID();

INSERT INTO `games` (`name`, `parent_game`, `type`, `lottery_time`, `result_time`, `open_days`, `status`) VALUES
('TEMP TEST MARKET OPEN', @test_parent_id, 'open', '23:55:00', '23:56:00', 'mon,tue,wed,thu,fri,sat,sun', 1),
('TEMP TEST MARKET CLOSE', @test_parent_id, 'close', '23:59:00', '23:59:00', 'mon,tue,wed,thu,fri,sat,sun', 1);

-- Link child games to parent
SET @open_id = LAST_INSERT_ID();
SET @close_id = @open_id + 1;

UPDATE `parent_games` SET `child_open_id` = @open_id, `child_close_id` = @close_id WHERE `id` = @test_parent_id;

-- Sample starline slots
INSERT INTO `starline` (`name`, `time`, `status`) VALUES
('Morning Star', '10:00:00', 1),
('Afternoon Star', '12:00:00', 1),
('Evening Star', '15:00:00', 1),
('Night Star', '18:00:00', 1),
('Late Night Star', '21:00:00', 1);
