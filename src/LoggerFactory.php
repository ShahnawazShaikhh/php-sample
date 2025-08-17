<?php
namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerFactory {
    public static function createLogger($channel = 'webapp') {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $logger = new Logger($channel);
        $logger->pushHandler(new StreamHandler($logDir . '/webapp.log', Logger::DEBUG));

        return $logger;
    }
}
