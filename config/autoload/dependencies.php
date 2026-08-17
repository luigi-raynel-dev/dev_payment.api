<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Domain\Health\HealthServiceInterface;
use App\Domain\Payment\PaymentRepositoryInterface;
use App\Domain\Shared\IdGeneratorInterface;
use App\Infrastructure\Health\HealthService;
use App\Infrastructure\Payment\PaymentRepository;
use App\Infrastructure\Shared\UuidIdGenerator;

/**
 * Dependency Inversion
 */
return [
  // Health
  HealthServiceInterface::class => HealthService::class,

  // Payment
  PaymentRepositoryInterface::class => PaymentRepository::class,
  IdGeneratorInterface::class => UuidIdGenerator::class,
];
