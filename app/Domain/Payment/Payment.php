<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use InvalidArgumentException;

final class Payment
{
  public const MIN_AMOUNT = 1;
  public const MAX_AMOUNT = 100000000;

  private const VALID_CURRENCIES = [
    'BRL',
    'USD',
    'EUR',
    'GBP',
  ];

  /**
   * @param string $id
   * @param int $amount
   * @param string $currency
   * @param string $description
   * @param PaymentStatus $status
   */
  public function __construct(
    private readonly string $id,
    private int $amount,
    private string $currency,
    private string $description,
    private PaymentStatus $status,
  ) {
    $this->validateAmount();
    $this->validateCurrency();
    $this->validateDescription();
  }

  public function id(): string
  {
    return $this->id;
  }

  public function amount(): int
  {
    return $this->amount;
  }

  public function currency(): string
  {
    return $this->currency;
  }

  public function description(): string
  {
    return $this->description;
  }

  public function status(): PaymentStatus
  {
    return $this->status;
  }

  public function markAsPaid(): void
  {
    $this->assertTransition(PaymentStatus::PAID);
    $this->status = PaymentStatus::PAID;
  }

  public function markAsFailed(): void
  {
    $this->assertTransition(PaymentStatus::FAILED);
    $this->status = PaymentStatus::FAILED;
  }

  public function cancel(): void
  {
    $this->assertTransition(PaymentStatus::CANCELED);
    $this->status = PaymentStatus::CANCELED;
  }

  private function validateAmount(): void
  {
    if ($this->amount < self::MIN_AMOUNT || $this->amount > self::MAX_AMOUNT) {
      throw new InvalidArgumentException('The payment amount must be greater than zero and less than or equal to 100000000.');
    }
  }

  private function validateCurrency(): void
  {
    if (! in_array(strtoupper($this->currency), self::VALID_CURRENCIES, true)) {
      throw new InvalidArgumentException('The payment currency must be a valid ISO 4217 code.');
    }

    $this->currency = strtoupper($this->currency);
  }

  private function validateDescription(): void
  {
    $trimmed = trim($this->description);

    if ($trimmed === '') {
      throw new InvalidArgumentException('The payment description cannot be empty.');
    }

    $this->description = $trimmed;
  }

  private function assertTransition(PaymentStatus $nextStatus): void
  {
    $allowed = match ($this->status) {
      PaymentStatus::PENDING => [PaymentStatus::PAID, PaymentStatus::FAILED, PaymentStatus::CANCELED],
      PaymentStatus::PAID => [],
      PaymentStatus::FAILED => [],
      PaymentStatus::CANCELED => [],
    };

    if (! in_array($nextStatus, $allowed, true)) {
      throw new InvalidArgumentException(sprintf(
        'The status transition from %s to %s is not allowed.',
        $this->status->value,
        $nextStatus->value,
      ));
    }
  }
}
