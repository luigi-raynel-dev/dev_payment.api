<?php

declare(strict_types=1);

namespace App\Domain\Payment;

enum PaymentStatus: string
{
  case PENDING = 'pending';
  case PAID = 'paid';
  case FAILED = 'failed';
  case CANCELED = 'canceled';

  public static function isValid(string $status): bool
  {
    return in_array($status, array_map(static fn(self $case): string => $case->value, self::cases()), true);
  }
}
