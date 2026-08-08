<?php

declare(strict_types=1);

namespace App\Domain\Health;

interface HealthServiceInterface
{
  public function getHealth(): array;
}
