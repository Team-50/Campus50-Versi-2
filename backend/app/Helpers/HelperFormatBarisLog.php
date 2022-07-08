<?php
 
namespace App\Helpers;
 
use Monolog\Formatter\LineFormatter;
 
class HelperFormatBarisLog
{
  const LoggerFormat = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
  const loggerTimeFormat = "Y-m-d H:i:s";
  /**
   * Customize the given logger instance.
   *
   * @param  \Illuminate\Log\Logger  $logger
   * @return void
   */
  public function __invoke($logger)
  {
    $logger->setTimeZone(new \DateTimeZone('Asia/Jakarta'));
    $logger->useMicrosecondTimestamps(false);
    foreach ($logger->getHandlers() as $handler) {
      $formatter = new LineFormatter(self::LoggerFormat, self::loggerTimeFormat, true, true);      
      $handler->setFormatter($formatter);
    }
  }
}