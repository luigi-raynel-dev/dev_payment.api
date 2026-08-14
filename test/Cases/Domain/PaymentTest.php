<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Domain;

use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentStatus;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PaymentTest extends TestCase
{
  public function testItCreatesPendingPaymentWithValidValues(): void
  {
    $payment = new Payment(
      id: 'pay_123',
      amount: 2500,
      currency: 'BRL',
      description: 'Pagamento de teste',
      status: PaymentStatus::PENDING,
    );

    $this->assertSame('pay_123', $payment->id());
    $this->assertSame(2500, $payment->amount());
    $this->assertSame('BRL', $payment->currency());
    $this->assertSame(PaymentStatus::PENDING, $payment->status());
    $this->assertSame('Pagamento de teste', $payment->description());
  }

  public function testItRejectsZeroOrNegativeAmount(): void
  {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The payment amount must be greater than zero and less than or equal to 100000000.');

    new Payment(
      id: 'pay_123',
      amount: 0,
      currency: 'BRL',
      description: 'Teste',
      status: PaymentStatus::PENDING,
    );
  }

  public function testItRejectsInvalidCurrency(): void
  {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The payment currency must be a valid ISO 4217 code.');

    new Payment(
      id: 'pay_123',
      amount: 2500,
      currency: 'XYZ',
      description: 'Teste',
      status: PaymentStatus::PENDING,
    );
  }

  public function testItAllowsValidStatusTransition(): void
  {
    $payment = new Payment(
      id: 'pay_123',
      amount: 2500,
      currency: 'BRL',
      description: 'Teste',
      status: PaymentStatus::PENDING,
    );

    $payment->markAsPaid();

    $this->assertSame(PaymentStatus::PAID, $payment->status());
  }

  public function testItRejectsInvalidTransition(): void
  {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The status transition from paid to paid is not allowed.');

    $payment = new Payment(
      id: 'pay_123',
      amount: 2500,
      currency: 'BRL',
      description: 'Teste',
      status: PaymentStatus::PENDING,
    );

    $payment->markAsPaid();
    $payment->markAsPaid();
  }
}
