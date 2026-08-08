<?php

declare(strict_types=1);

namespace App\Application\Health;

use App\Domain\Health\HealthServiceInterface;

final class CheckHealthUseCase
{
  public function __construct(private readonly HealthServiceInterface $healthService) {}

  public function execute(): array
  {
    return $this->healthService->getHealth();
  }
}
