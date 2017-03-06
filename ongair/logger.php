<?php
  
  use Aws\CloudWatchLogs\CloudWatchLogsClient;
  use Maxbanton\Cwh\Handler\CloudWatch;
  use Monolog\Logger;
  use Monolog\Handler\ErrorLogHandler;

  class OngairLogger {
    static protected $instance;

    static public function getLogger() {
      if (! self::$instance) {
        self::configureInstance();
      }

      return self::$instance;
    }

    static protected function configureInstance() {
      $credentials = [
        'region' => 'ap-southeast-1',
        'version' => 'latest',
        'credentials' => [
          'key' => getenv('aws_key_id'),
          'secret' => getenv('aws_secret_access_key')
        ]
      ];

      $client = new CloudWatchLogsClient($credentials);
      $logGroupName = 'ongair-whatsapp';
      $logStreamName = getenv('account').'.'.getenv('env');

      $handler = new CloudWatch($client, $logGroupName, $logStreamName);
      $stream = new ErrorLogHandler();

      self::$instance = new Logger('name');
      self::$instance->pushHandler($handler);

      if (getenv('env') == 'development')
        self::$instance->pushHandler($stream);
    }
  }