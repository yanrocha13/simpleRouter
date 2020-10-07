<?php
declare(strict_types=1);

namespace Demo\Repository\Api;

interface LoggerRepositoryInterface
{
    public function createModelLog(string $model, string $message, int $level, ?array $context = null): void;
    public function createViewLog(string $view, string $message, int $level, ?array $context = null): void;
}
