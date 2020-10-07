<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Repository\Api\LoggerRepositoryInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerRepository implements LoggerRepositoryInterface
{
    /**
     * @param string $model
     * @param string $message
     * @param int $level
     * @param array|null $context
     */
    public function createModelLog(string $model, string $message, int $level, ?array $context = null ): void
    {
        $log = new Logger($model);
        $log->pushHandler(new StreamHandler(__DIR__ . '/../../etc/Log/model.log', $level));
        $this->logMessageAndContext($level, $log,$message, $context);
    }

    /**
     * @param string $view
     * @param string $message
     * @param int $level
     * @param array|null $context
     */
    public function createViewLog(string $view, string $message, int $level, ?array $context = null): void
    {
        $log = new Logger($view);
        $log->pushHandler(new StreamHandler(__DIR__ . '/../../etc/Log/view.log', $level));
        $this->logMessageAndContext($level, $log,$message, $context);
    }

    /**
     * @param int $level
     * @param Logger $log
     * @param string|null $message
     * @param array|null $context
     */
    private function logMessageAndContext(int $level, Logger $log, ?string $message = null , ?array $context = null)
    {
        switch($level)
        {
            case 100:
                $log->debug($message);
                break;
            case 200:
                $log->info($message);
                break;
            case 250:
                $log->notice($message);
                break;
            case 300:
                $log->warning($message);
                break;
            case 400:
                $log->error($message);
                break;
            case 500:
                $log->critical($message);
                break;
            case 550:
                $log->alert($message);
                break;
            case 600:
                $log->emergency($message);
                break;
        }
    }
}
