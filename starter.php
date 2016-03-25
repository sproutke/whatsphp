<?php
  require 'vendor/autoload.php';
  require_once('lib/activerecord/ActiveRecord.php');
  require_once('models/Account.php'); 
  require_once('util.php');

  Dotenv::load(__DIR__);

  _init_db();
  $count = 0;
  $limit = getenv('BATCH_COUNT');
  $accounts = Account::all(array('setup' => true));
  foreach ($accounts as $account) {
    $number = $account->phone_number;
    if (!service_running($number) && $count < $limit) {
     	$service_name = service_from_phone_number($number);
	    $service = service_name($service_name);

      if (!empty($service)) {
        echo "Account: ".$account->name." - ".$account->phone_number."  needs to start : ".$service.PHP_EOL;
        shell_exec('rm /var/www/whatsapi-official/src/wadata/*'.$account->phone_number.'*');      
        $running = shell_exec('sudo service '.$service.' start');
        echo "Account status: ".$running;
        sleep(5);
        $count++;  
      }      
    }
  }