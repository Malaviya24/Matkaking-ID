<?php

	
function get_userNameById($user_id) {
    global $con;
	$sql = "SELECT name FROM users WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->name;
	}
	
function get_userMobileById($user_id) {
    global $con;
	$sql = "SELECT mobile FROM users WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->mobile;
	}
	
	
function get_userDevice_infoById($user_id) {
    global $con;
	$sql = "SELECT device_info FROM users WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->device_info;
	}

function get_userDeviceTokenById($user_id) {
    global $con;
	$sql = "SELECT device_token FROM users WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->device_token;
	}
	
function get_userDeviceIDById($user_id) {
    global $con;
	$sql = "SELECT device_id FROM users WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->device_id;
	}
	
function UpdateDeviceTokenUserTable($user_id,$device_token) {
    global $con;
	$sql = "UPDATE users set device_token ='$device_token' WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);

	}
	
	
function get_MpinByPhone_no($phone) {
    global $con;
    $phone = '+91'.$phone;
	$sql = "SELECT mpin FROM users WHERE mobile='$phone' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->mpin;
	}
	

function UpdateDeviceIDUserTable($user_id,$device_id) {
    global $con;
	$sql = "UPDATE users set device_id ='$device_id' WHERE id='$user_id'";
	$result = mysqli_query($con, $sql);

	}
	
function UpdateDeviceInfoUserTable($user_id,$device_info) {
    global $con;
	$sql = "UPDATE users set device_info ='$device_info' WHERE id='$user_id'";
	$result = mysqli_query($con, $sql);

	}
	
	
function get_username1ById($user_id) {
    global $con;
	$sql = "SELECT username FROM users WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->username;
	}
	
function checkUserId($user_id) {
    global $con;
	$sql = "SELECT id FROM users WHERE id='$user_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->id;
	}
	
function UpdateBalanceInUserTable($user_id,$balance) {
    global $con;
    $user_id = (int) $user_id;
    $balance = app_money_value($balance);

    mysqli_begin_transaction($con);
    $stmt = mysqli_prepare($con, "SELECT id FROM users WHERE id = ? LIMIT 1 FOR UPDATE");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || !mysqli_fetch_assoc($result)) {
        mysqli_rollback($con);
        return false;
    }

    $update = mysqli_prepare($con, "UPDATE users SET balance = ? WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($update, 'di', $balance, $user_id);
    if (!mysqli_stmt_execute($update)) {
        mysqli_rollback($con);
        return false;
    }

    mysqli_commit($con);
    return true;
	}
	

function get_gameNameById($game_id) {
    global $con;
	$sql = "SELECT name FROM games WHERE id='$game_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->name;
	}
	
function check_late_night($game_id) {
    global $con;
	$sql = "SELECT late_night FROM games WHERE id='$game_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->late_night;
	}
	
function get_gameTimeById($game_id) {
    global $con;
	$sql = "SELECT lottery_time FROM games WHERE id='$game_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->lottery_time;
	}

function IsTempTestMarketGame($game_id) {
    global $con;
    $game_id = intval($game_id);
    if ($game_id <= 0) {
        return false;
    }

    $sql = "SELECT pg.name FROM games g INNER JOIN parent_games pg ON pg.id = g.parent_game WHERE g.id='$game_id' limit 1";
    $result = mysqli_query($con, $sql);
    $value = $result ? mysqli_fetch_object($result) : null;
    return $value && strtoupper($value->name) === 'TEMP TEST MARKET';
	}

function IsTempTestMarketParent($parent_game_id) {
    global $con;
    $parent_game_id = intval($parent_game_id);
    if ($parent_game_id <= 0) {
        return false;
    }

    $sql = "SELECT name FROM parent_games WHERE id='$parent_game_id' limit 1";
    $result = mysqli_query($con, $sql);
    $value = $result ? mysqli_fetch_object($result) : null;
    return $value && strtoupper($value->name) === 'TEMP TEST MARKET';
	}
	
function get_parentGameById($id) {
    global $con;
	$sql = "SELECT name FROM parent_games WHERE id='$id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->name;
	}
	
	
	
function get_parentIdById($id) {
    global $con;
	$sql = "SELECT parent_game FROM games WHERE id='$id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->parent_game;
	}

function matka_result_digit($result) {
	if (!$result) {
		return '';
	}

	$value = mysqli_fetch_object($result);
	return ($value && isset($value->digit)) ? (string) $value->digit : '';
	}

function matka_panna_ank($panna) {
	$panna = (string) $panna;
	if ($panna === '') {
		return '';
	}

	$ank = 0;
	for ($i = 0; $i < strlen($panna); $i++) {
		$ank += (int) $panna[$i];
	}
	return substr((string) $ank, -1);
	}

function matka_format_panna_result($panna, $side = 'open') {
	$panna = (string) $panna;
	$ank = matka_panna_ank($panna);
	if ($panna === '' || $ank === '') {
		return $side === 'close' ? '*-***' : '***-*';
	}

	return $side === 'close' ? $ank . '-' . $panna : $panna . '-' . $ank;
	}
	
function GetLastOpneResultByid($game_id,$temp_Date) {
    global $con;
    
	if(substr(date('h:i:s'), 0, 2) < 10 && date('A') =='AM')
	{
	$sql = "SELECT digit FROM result WHERE game_id='$game_id' and game_type='single_patti' order by date DESC limit 1";
	}else{
	$sql = "SELECT digit FROM result WHERE game_id='$game_id' and game_type='single_patti' and date='$temp_Date' limit 1";
	}
	$result = mysqli_query($con, $sql);
	return matka_format_panna_result(matka_result_digit($result), 'open');
	}
	
function GetLastCloseResultByid($game_id,$temp_Date) {
    global $con;
    
	if(substr(date('h:i:s'), 0, 2) < 10 && date('A') =='AM')
	{
	$sql = "SELECT digit FROM result WHERE game_id='$game_id' and game_type='single_patti' order by date DESC limit 1";
	}else{
	$sql = "SELECT digit FROM result WHERE game_id='$game_id' and game_type='single_patti' and date='$temp_Date' limit 1";
	}
	$result = mysqli_query($con, $sql);
	return matka_format_panna_result(matka_result_digit($result), 'close');
	}

	
function get_ChildOpen($id) {
    global $con;
	$sql = "SELECT id FROM games WHERE parent_game='$id' and type='open' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->id;
	}
	
function get_StarlineResult($id) {
    global $con;
    $today = date('Y-m-d');
	$sql = "SELECT digit FROM starline_result WHERE game_id='$id' and date='$today' limit 1";
	$result = mysqli_query($con, $sql);
	if (!$result) {
		return '***';
	}
	$value = mysqli_fetch_object($result);
	if ($value && isset($value->digit) && $value->digit !== null && $value->digit !== '') {
		return $value->digit;
	}
	return '***';
	}
	
function get_Starlinetime($id) {
    global $con;
	$sql = "SELECT time FROM starline WHERE id='$id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->time;
	}  
	
function get_Jackpottime($id) {
    global $con;
	$sql = "SELECT lottery_time FROM jackpot_games WHERE id='$id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	$lottery_time = $value->lottery_time;
	$lottery_time =  date("g:i A", strtotime($lottery_time));
	
	return $lottery_time;
	}
	
function get_JackpotName($id) {
    global $con;
	$sql = "SELECT name FROM jackpot_games WHERE id='$id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->name;
	}

	
function get_ChildClose($id) {
    global $con;
	$sql = "SELECT id FROM games WHERE parent_game='$id' and type='close' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->id;
	}
	
	
function get_GameTypeById($id) {
    global $con;
	$sql = "SELECT type FROM games WHERE id='$id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->type;
	}
	
function get_RateSingle() {
    global $con;
	$sql = "SELECT rate FROM game_rate WHERE id='1' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_RateJodi() {
    global $con;
	$sql = "SELECT rate FROM game_rate WHERE id='2' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_RateSinglePatti() {
    global $con;
	$sql = "SELECT rate FROM game_rate WHERE id='3' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_RateDoublePatti() {
    global $con;
	$sql = "SELECT rate FROM game_rate WHERE id='4' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_RateTriplePatti() {
    global $con;
	$sql = "SELECT rate FROM game_rate WHERE id='5' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_RateHalfSangam() {
    global $con;
	$sql = "SELECT rate FROM game_rate WHERE id='6' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_RateFullSangam() {
    global $con;
	$sql = "SELECT rate FROM game_rate WHERE id='7' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_StarlineSingle() {
    global $con;
	$sql = "SELECT srate FROM game_rate WHERE id='1' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->srate;
	}
	
function get_StarlineSinglePatti() {
    global $con;
	$sql = "SELECT srate FROM game_rate WHERE id='3' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->srate;
	}
	
function get_StarlineDoublePatti() {
    global $con;
	$sql = "SELECT srate FROM game_rate WHERE id='4' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->srate;
	}
	
function get_StarlineTriplePatti() {
    global $con;
	$sql = "SELECT srate FROM game_rate WHERE id='5' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->srate;
	}
	
	
function get_JackpotOpenRate() {
    global $con;
	$sql = "SELECT rate FROM jackpot_rate WHERE id='1' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_JackpotCloseRate() {
    global $con;
	$sql = "SELECT rate FROM jackpot_rate WHERE id='2' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
function get_JackpotJodiRate() {
    global $con;
	$sql = "SELECT rate FROM jackpot_rate WHERE id='3' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->rate;
	}
	
	
function get_lastBalance($user_id) {
    global $con;
	$sql = "SELECT balance FROM users WHERE id='$user_id' order by id DESC limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    	if($value){
    	return floor($value->balance);
    	}else{return 0;}
	}
	
function UpdateUserBalance($user_id,$amount) {
    global $con;
	$sql = "update users set balance = (balance + ".$amount.") where id='".$user_id."'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	}
	
function GetPannaType($digit){
    global $con;
            $sp =array();
			$dp =array();
			$tp =array();
			
			//get single patti 
			$qry11 =  "SELECT * FROM sp where 1";
			$result11 = mysqli_query($con, $qry11);
			$i=0;
			while($row11 = mysqli_fetch_array($result11)){ 
			$sp[$i] = $row11['num'];
			$i++;
			}
			
			//get double patti
			$qry12 =  "SELECT * FROM dp where 1";
			$result12 = mysqli_query($con, $qry12);
			$i=0;
			while($row12 = mysqli_fetch_array($result12)){ 
			$dp[$i] = $row12['num'];
			$i++;
			}
			
			//get Tripple patti
			$qry13 =  "SELECT * FROM tp where 1";
			$result13 = mysqli_query($con, $qry13);
			$i=0;
			while($row13 = mysqli_fetch_array($result13)){ 
			$tp[$i] = $row13['num'];
			$i++;
			}
			
			$digit_type='';
			
			if(in_array($digit, $sp )){
				$digit_type='single_patti';
			
			}elseif(in_array($digit, $dp )){
				$digit_type='double_patti';
			
			}elseif(in_array($digit, $tp )){
				$digit_type='triple_patti';
			}else{}
			
			return $digit_type; 
}
	
function IsDeviceBlocked($device_id) {
    global $con; 
	$sql = "SELECT count(*) as num from blocked_devices WHERE device_id='$device_id'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    return $value->num;
	}
	
	
function allUserBalance() {
    global $con;
	$sql = "SELECT sum(balance) as all_user_balance FROM users";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->all_user_balance;
	}
	

	
function TodayPlayed($date) {
    global $con;
	$sql = "SELECT sum(amount) as total_played FROM user_transaction WHERE date='$date' and type='bid' and starline !='1'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    return $value->total_played;
	}
	
	
function TodayWin($date) {
    global $con;
	$sql = "SELECT sum(amount) as total_win FROM user_transaction WHERE date='$date' and type='win' and starline !='1'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    	if($value->total_win >0)
    	{
        return $value->total_win;
    	}else{
    	    return 0;
    	}
	}
	
function TodayPlayed_starline($date) {
    global $con;
	$sql = "SELECT sum(amount) as total_played FROM user_transaction WHERE date='$date' and type='bid' and starline ='1'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    return $value->total_played;
	}
	
	
function TodayWin_starline($date) {
    global $con;
	$sql = "SELECT sum(amount) as total_win FROM user_transaction WHERE date='$date' and type='win' and starline ='1'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    	if($value->total_win >0)
    	{
        return $value->total_win;
    	}else{
    	    return 0;
    	}
	}
	
function TodayCredit($date) {
    global $con;
	$sql = "SELECT sum(amount) as today_credit FROM user_transaction WHERE date='$date' and type='deposit' and debit_credit='credit'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    return $value->today_credit;
	}
	
function TodayDebit($date) {
    global $con;
	$sql = "SELECT sum(amount) as today_debit FROM user_transaction WHERE date='$date' and (type='withdraw' or type='deposit') and debit_credit='debit'";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
    return $value->today_debit;
	}
	
function GetParentOpenTime($game_id) {
    global $con;
    $child_games = mysqli_query($con,"SELECT lottery_time FROM `games` WHERE parent_game='$game_id' and type='open' ");
        while ($child_row = mysqli_fetch_array($child_games)){
            $open_time = $child_row['lottery_time'];
            $open_time =  date("g:i A", strtotime($open_time));
        }

    return $open_time;
	}
	
function GetParentCloseTime($game_id) {
    global $con;
    $child_games = mysqli_query($con,"SELECT lottery_time FROM `games` WHERE parent_game='$game_id' and type='close' ");
        while ($child_row = mysqli_fetch_array($child_games)){
            $close_time = $child_row['lottery_time'];
            $close_time =  date("g:i A", strtotime($close_time));
        }

    return $close_time;
	}
	
function GetParentOpenResultTime($game_id) {
    global $con;
    $child_games = mysqli_query($con,"SELECT result_time FROM `games` WHERE parent_game='$game_id' and type='open' ");
        while ($child_row = mysqli_fetch_array($child_games)){
            $open_result_time = $child_row['result_time'];
            $open_result_time =  date("g:i A", strtotime($open_result_time));
        }

    return $open_result_time;
	}
	
function GetParentCloseResultTime($game_id) {
    global $con;
    $child_games = mysqli_query($con,"SELECT result_time FROM `games` WHERE parent_game='$game_id' and type='close' ");
        while ($child_row = mysqli_fetch_array($child_games)){
            $close_result_time = $child_row['result_time'];
            $close_result_time =  date("g:i A", strtotime($close_result_time));
        }

    return $close_result_time;
	}
	
function GetParentOpendays($game_id) {
    global $con;
    $child_games = mysqli_query($con,"SELECT open_days FROM `games` WHERE parent_game='$game_id' and type='open' ");
        while ($child_row = mysqli_fetch_array($child_games)){
            $open_days = $child_row['open_days'];
        }

    return $open_days;
	}
	
function GetOpneResultByid($game_id) {
    global $con;
    
    //show back date result before morning 9
    if(substr(date('H:i:s'), 0, 2) < '8' && date('A') =='AM')
    	{
    	    $today = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));
		}else{
            $today = date('Y-m-d');
		}
    
	$sql = "SELECT digit FROM result WHERE game_id='$game_id' and date='$today' and game_type='single_patti' limit 1";
	
	$result = mysqli_query($con, $sql);
	return matka_format_panna_result(matka_result_digit($result), 'open');
	}
	
function GetCloseResultByid($game_id) {
    global $con;
    
    //show back date result before morning 9
    if(substr(date('H:i:s'), 0, 2) < '8' && date('A') =='AM')
    	{
    	    $today = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));
		}else{
            $today = date('Y-m-d');
		}
	$sql = "SELECT digit FROM result WHERE game_id='$game_id' and date='$today' and game_type='single_patti' limit 1";
	$result = mysqli_query($con, $sql);
	return matka_format_panna_result(matka_result_digit($result), 'close');
	}
	
function GetStrlineResultByid($game_id) {
    global $con;
	$today = date('Y-m-d');
	$sql = "SELECT digit FROM starline_result WHERE game_id='$game_id' and date='$today' and game_type='single_patti' limit 1";
	$result = mysqli_query($con, $sql);
	return matka_format_panna_result(matka_result_digit($result), 'open');
	}
	
function GetStrlineResultByDate($game_id,$date) {
    global $con;
	$today = $date;
	$sql = "SELECT digit FROM starline_result WHERE game_id='$game_id' and date='$today' and game_type='single_patti' limit 1";
	$result = mysqli_query($con, $sql);
	return matka_format_panna_result(matka_result_digit($result), 'open');
	}
	
	
function GetJackpotResultByid($game_id) {
    global $con;
	$today = date('Y-m-d');
	$sql = "SELECT digit FROM jackpot_result WHERE game_id='$game_id' and date='$today' and game_type='jodi' limit 1";
	$result = mysqli_query($con, $sql);
	$jodi = matka_result_digit($result);
	return $jodi !== '' ? $jodi : '**';
	}
	
function GetJackpotResultByDate($game_id,$date) {
    global $con;
	$today = $date;
	$sql = "SELECT digit FROM jackpot_result WHERE game_id='$game_id' and date='$today' and game_type='jodi' limit 1";
	$result = mysqli_query($con, $sql);
	$jodi = matka_result_digit($result);
	return $jodi !== '' ? $jodi : '**';
	}
	
	
function get_SettingValue($name) {
    global $con;
	$sql = "SELECT value FROM settings WHERE name='$name' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	return $value->value;
	}
	
function get_GamePlayDates($game_id) {
    global $con;
	$sql = "SELECT open_days FROM games WHERE id='$game_id' limit 1";
	$result = mysqli_query($con, $sql);
	$value = mysqli_fetch_object($result);
	$open_days = $value->open_days;
	$game_days = explode(",", $open_days);
	$dates = array();
	$count = 0;
	
	    for($i=0;$i<=5;$i++)
	    {   
	        $date = date('Y-m-d', strtotime(date('Y-m-d') . ' +'.$i.' day'));
	        $day = strtolower(date('D', strtotime($date)));
	        if(in_array($day, $game_days))
	        {
	        $dates[$count] = $date;
	        $count ++;
	        }
	    }
	    
	return $dates;
			
	}

function generateRandomString($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
	
	
function randomOTP($length) {
			$random= "";
			$data = "123456789";
			
			for($i = 0; $i < $length; $i++) {
				$random .= substr($data, random_int(0, strlen($data) - 1), 1);
			}
			return $random;
		}
		
function sendRequest($param){
			$url = $param['url'];
			$postData = $param['postData'];

			$ch = curl_init();
			curl_setopt_array($ch, array(
			    CURLOPT_URL => $url,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_POST => true,
			    CURLOPT_POSTFIELDS => $postData
			    //,CURLOPT_FOLLOWLOCATION => true
			));

			//Ignore SSL certificate verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			//get response
			$output = curl_exec($ch);

			//Print error if any
			if(curl_errno($ch)) {
			    return curl_error($ch);
			}
			curl_close($ch);
			return $output;
		}
		
		
function sendPushNotification($to,$title,$description,$action_url){
            $data = array(
                "to" => $to,  
                "priority"=> "high",
                
                "data" => array(
                    "title" => $title, 
                    "description" => $description, 
                    "date" => date('y-m-d'), 
                    "time" => date('h:i A'),
					"redirection" => 0,
					"redirection_to" => 'home_page',
					"game_id" => 0,
                    "icon" => $home_url.'img/icon.png',
                    "url" => $action_url)
                ); 
    
            $data_string = json_encode($data); 
            $headers = array ( 'Authorization: key=' . API_ACCESS_KEY, 'Content-Type: application/json' ); 
            $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
            curl_setopt( $ch,CURLOPT_POST, true ); 
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
            curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string); 
            $result = curl_exec($ch); 
            curl_close ($ch); 
			
			
			$data1 = array(
                "to" => $to,  
                "priority"=> "high",
                
                "data" => array(
                    "title" => $title, 
                    "description" => $description, 
                    "date" => date('y-m-d'), 
                    "time" => date('h:i A'),
					"redirection" => 0,
					"redirection_to" => 'home_page',
					"game_id" => 0,
                    "icon" => $home_url.'img/icon.png',
                    "url" => $action_url)
                ); 
    
            $data_string = json_encode($data1); 
            $headers = array ( 'Authorization: key=' . API_ACCESS_KEY2, 'Content-Type: application/json' ); 
            $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
            curl_setopt( $ch,CURLOPT_POST, true ); 
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
            curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string); 
            $result = curl_exec($ch); 
			//print_r($data_string);
            curl_close ($ch); 
}



// Function to create a base64 URL-encoded string
function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function sendPushNotificationFCM($topic,$token,$title,$description,$action_url,$redirection,$redirection_to,$game_id){
	
	$date = date('Y-m-d');
	$time = date('h:i:s');
	
$serviceAccountPath = SERVICE_ACCOUNT_KEY_FCM; // Update this path in connect.php file
$serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

// Set the required values
$privateKey = $serviceAccount['private_key'];
$clientEmail = $serviceAccount['client_email'];
$projectId = $serviceAccount['project_id'];

// Create a JWT for authentication
$now = time();
$expires = $now + 3600; // Token valid for 1 hour

$header = [
    'alg' => 'RS256',
    'typ' => 'JWT'
];

$payload = [
    'iss' => $clientEmail,
    'sub' => $clientEmail,
    'aud' => 'https://oauth2.googleapis.com/token',
    'iat' => $now,
    'exp' => $expires,
    'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
];

// Encode the header and payload
$base64UrlHeader = base64UrlEncode(json_encode($header));
$base64UrlPayload = base64UrlEncode(json_encode($payload));

// Create the signature
$signature = '';
openssl_sign($base64UrlHeader . '.' . $base64UrlPayload, $signature, $privateKey, 'SHA256');
$base64UrlSignature = base64UrlEncode($signature);

// Create the JWT
$jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

// Get an OAuth2 access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $jwt
]));

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);
$accessToken = $data['access_token'];

if($topic !=''){
$message = [
    "message" => [
        "topic" => $topic,
        "data" => [
            "title" => $title,
            "description" => $description,
            "date" => $date,
            "time" => $time,
            "icon" => $home_url.'img/icon.png',
            "redirection" => $redirection,
			"redirection_to" => $redirection_to,
			"game_id" => $game_id,
			"url" => $action_url
        ]
    ]
]; 

}else{
$message = [
    "message" => [
        "token" => $token,
        "data" => [
            "title" => $title,
            "description" => $description,
            "date" => $date,
            "time" => $time,
            "icon" => $home_url.'img/icon.png',
            "redirection" => $redirection,
			"redirection_to" => $redirection_to,
			"game_id" => $game_id,
			"url" => $action_url
        ]
    ]
]; 	
}

// Send the notification
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

$result = curl_exec($ch);
curl_close($ch);

return $result;
}
	

function sendPushNotification_oneSignal($title,$description){
    if(0){
	$content = array("en" => $description);    
    $heading = array("en" => $title);
	$priority = 10;
    $fields = array(
        'app_id' => "279dcf96-3362-447a-93c5-00000",
        'included_segments' => array('All'),
        'data' => array("foo" => "bar"),
        'priority' => $priority,
        'contents' => $content,
        'headings' => $heading
    );

    $fields = json_encode($fields);
//print("\nJSON sent:\n");
//print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic 1111111'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

    $response = curl_exec($ch);
    curl_close($ch);
    }
    return 0;
}


	





?>
