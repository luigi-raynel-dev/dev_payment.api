<?php

declare(strict_types=1);

namespace App\Application\Payment;

use App\Domain\Payment\Payment;

final class CreatePaymentOutput
{
  public function __construct(
    public readonly string $id,
    public readonly int $amount,
    public readonly string $currency,
    public readonly string $description,
    public readonly string $status,
  ) {}

  public static function fromPayment(Payment $payment): self
  {
    return new self(
      id: $payment->id(),
      amount: $payment->amount(),
      currency: $payment->currency(),
      description: $payment->description(),
      status: $payment->status()->value,
    );
  }
}
