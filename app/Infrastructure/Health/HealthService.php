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
      'service' => $this->config->get('app.name', 'dev-payment-api'),
      'version' => $this->config->get('app.version', '0.2.0'),
      'environment' => $this->config->get('app.env', 'dev'),
      'timestamp' => gmdate('c'),
    ];
  }
}
