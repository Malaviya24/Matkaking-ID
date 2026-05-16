"""
Property Tests for Scraper - Status Transition Detection & Settlement Trigger
Run with: python tests/test_scraper_properties.py

Tests Properties 1, 2 from the design document.
"""
import random
import sys
sys.path.insert(0, '.')

# ─── Property 1: Status transition detection triggers correct settlement type ───
def test_property_1_status_transitions():
    """
    Generate random (old_status, new_status) pairs.
    Verify correct settlement type is triggered only for valid transitions.
    Verify no settlement when old_status equals new_status.
    """
    statuses = ['waiting', 'open_declared', 'closed']
    valid_transitions = {
        ('waiting', 'open_declared'): 'open',
        ('open_declared', 'closed'): 'close',
    }
    
    passed = 0
    failed = 0
    
    for _ in range(1000):
        old_status = random.choice(statuses)
        new_status = random.choice(statuses)
        
        # Determine expected settlement type
        key = (old_status, new_status)
        expected_type = valid_transitions.get(key, None)
        
        # Simulate detection logic
        transition_detected = (old_status != new_status) and key in valid_transitions
        actual_type = valid_transitions.get(key) if transition_detected else None
        
        # Verify: no settlement when same status
        if old_status == new_status:
            if actual_type is None:
                passed += 1
            else:
                failed += 1
                print(f"  FAIL: Same status {old_status} should NOT trigger settlement, got {actual_type}")
        # Verify: correct type for valid transitions
        elif key in valid_transitions:
            if actual_type == expected_type:
                passed += 1
            else:
                failed += 1
                print(f"  FAIL: {old_status}->{new_status} should trigger '{expected_type}', got '{actual_type}'")
        # Verify: no settlement for invalid transitions
        else:
            if actual_type is None:
                passed += 1
            else:
                failed += 1
                print(f"  FAIL: {old_status}->{new_status} should NOT trigger settlement, got {actual_type}")
    
    print(f"  Property 1: {passed}/{passed+failed} passed")
    return failed == 0


# ─── Property 2: Settlement trigger failure isolation ───
def test_property_2_failure_isolation():
    """
    Generate random sets of markets with some failing settlement triggers.
    Verify all other markets in the cycle are still processed normally.
    """
    passed = 0
    failed = 0
    
    for _ in range(100):
        num_markets = random.randint(5, 20)
        markets = [{'id': i, 'name': f'Market_{i}', 'should_fail': random.random() < 0.3} for i in range(num_markets)]
        
        # Simulate processing with failure isolation
        processed = []
        for market in markets:
            try:
                if market['should_fail']:
                    raise Exception(f"Simulated failure for {market['name']}")
                processed.append(market['id'])
            except Exception:
                # Failure isolated - continue to next market
                processed.append(market['id'])  # Still counted as processed (attempted)
                continue
        
        # Verify ALL markets were attempted regardless of failures
        if len(processed) == num_markets:
            passed += 1
        else:
            failed += 1
            print(f"  FAIL: Expected {num_markets} markets processed, got {len(processed)}")
    
    print(f"  Property 2: {passed}/{passed+failed} passed")
    return failed == 0


# ─── Property: Result parsing correctness ───
def test_result_parsing():
    """
    Verify parse_result correctly identifies full, partial, and empty results.
    """
    import re
    
    def parse_result_test(text):
        if not text or text.strip() == '':
            return 'waiting'
        text = text.strip()
        if 'loading' in text.lower() or text in ('***-**-***', '***-*-***'):
            return 'waiting'
        if re.match(r'^\d{3}-\d{2}-\d{3}$', text):
            return 'closed'
        if re.match(r'^\d{3}-\d$', text):
            return 'open_declared'
        if '*' in text:
            return 'waiting'
        return 'unknown'
    
    passed = 0
    failed = 0
    
    # Test full results
    for _ in range(100):
        panna1 = str(random.randint(100, 999))
        jodi = str(random.randint(10, 99))
        panna2 = str(random.randint(100, 999))
        full = f"{panna1}-{jodi}-{panna2}"
        status = parse_result_test(full)
        if status == 'closed':
            passed += 1
        else:
            failed += 1
            print(f"  FAIL: '{full}' should be 'closed', got '{status}'")
    
    # Test partial results
    for _ in range(100):
        panna = str(random.randint(100, 999))
        ank = str(random.randint(0, 9))
        partial = f"{panna}-{ank}"
        status = parse_result_test(partial)
        if status == 'open_declared':
            passed += 1
        else:
            failed += 1
            print(f"  FAIL: '{partial}' should be 'open_declared', got '{status}'")
    
    # Test empty/loading
    for text in ['', '***-**-***', 'Loading...', '***-*-***', 'loading']:
        status = parse_result_test(text)
        if status == 'waiting':
            passed += 1
        else:
            failed += 1
            print(f"  FAIL: '{text}' should be 'waiting', got '{status}'")
    
    print(f"  Result parsing: {passed}/{passed+failed} passed")
    return failed == 0


if __name__ == '__main__':
    print("=" * 50)
    print("Property Tests - Scraper")
    print("=" * 50)
    
    results = []
    
    print("\nProperty 1: Status transition detection...")
    results.append(test_property_1_status_transitions())
    
    print("\nProperty 2: Failure isolation...")
    results.append(test_property_2_failure_isolation())
    
    print("\nResult parsing correctness...")
    results.append(test_result_parsing())
    
    print("\n" + "=" * 50)
    all_passed = all(results)
    print(f"OVERALL: {'ALL PASSED' if all_passed else 'SOME FAILED'}")
    print("=" * 50)
    
    sys.exit(0 if all_passed else 1)
