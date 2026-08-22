<?php

declare(strict_types=1);

namespace App\Application\Payment;

final class CreatePaymentInput
{
  public function __construct(
    public readonly int $amount,
    public readonly string $currency,
    public readonly string $description,
    public readonly string $status = 'pending',
  ) {}
}
