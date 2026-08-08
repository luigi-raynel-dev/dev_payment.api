<?php

declare(strict_types=1);

namespace App\Infrastructure\Health;

use App\Domain\Health\HealthServiceInterface;
use Hyperf\Contract\ConfigInterface;

final class HealthService implements HealthServiceInterface
{
  public function __construct(private readonly ConfigInterface $config) {}

  public function getHealth(): array
  {
    return [
      'status' => 'UP',
      'service' => $this->config->get('app.name', $this->config->get('app_name', 'dev-payment-api')),
      'version' => $this->config->get('app.version', $this->config->get('app_version', '1.0.0')),
      'environment' => $this->config->get('app.env', $this->config->get('app_env', 'dev')),
      'timestamp' => gmdate('c'),
      'checks' => [
        'database' => $this->checkDatabase(),
        'redis' => $this->checkRedis(),
      ],
    ];
  }

  private function checkDatabase(): string
  {
    try {
      $config = $this->config->get('databases.default', []);
      if (!isset($config['host'], $config['database'], $config['username'])) {
        return 'DOWN';
      }

      $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['database'],
        $config['charset'] ?? 'utf8mb4'
      );

      $pdo = new \PDO(
        $dsn,
        $config['username'],
        $config['password'] ?? '',
        [
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]
      );

      $pdo->query('SELECT 1');

      return "UP";
    } catch (\Throwable) {
      return "DOWN";
    }
  }

  private function checkRedis(): string
  {
    try {
      $config = $this->config->get('redis.default', []);
      if (!class_exists('Redis')) {
        return 'DOWN';
      }

      $redis = new \Redis();
      $redis->connect(
        $config['host'] ?? '127.0.0.1',
        (int) ($config['port'] ?? 6379),
        2.0
      );

      if (isset($config['auth']) && $config['auth'] !== null && $config['auth'] !== '') {
        $redis->auth($config['auth']);
      }

      if (isset($config['db'])) {
        $redis->select((int) $config['db']);
      }

      $redis->ping();

      return 'UP';
    } catch (\Throwable) {
      return 'DOWN';
    }
  }
}
