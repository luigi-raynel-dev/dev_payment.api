<?php

declare(strict_types=1);

namespace App\Domain\Payment;

interface PaymentRepositoryInterface
{
  public function save(Payment $payment): void;

  public function findById(string $id): ?Payment;
}
