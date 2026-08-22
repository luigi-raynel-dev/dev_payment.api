<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Infrastructure\Payment;

use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentStatus;
use App\Infrastructure\Payment\PaymentRepository;
use App\Model\Payment as PaymentModel;
use Hyperf\Testing\TestCase;

final class PaymentRepositoryTest extends TestCase
{
  private PaymentRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();

    $this->repository = new PaymentRepository();
  }

  protected function tearDown(): void
  {
    PaymentModel::query()
      ->whereIn('id', [
        'pay_repository_test_001',
        'pay_repository_test_002',
      ])
      ->delete();

    restore_error_handler();
    restore_exception_handler();

    parent::tearDown();
  }

  public function testItSavesAndFindsPayment(): void
  {
    // Arrange
    $payment = new Payment(
      id: 'pay_repository_test_001',
      amount: 2500,
      currency: 'BRL',
      description: 'Pagamento de integração',
      status: PaymentStatus::PENDING,
    );

    // Act
    $this->repository->save($payment);

    $foundPayment = $this->repository->findById($payment->id());

    // Assert
    $this->assertNotNull($foundPayment);
    $this->assertSame($payment->id(), $foundPayment->id());
    $this->assertSame($payment->amount(), $foundPayment->amount());
    $this->assertSame($payment->currency(), $foundPayment->currency());
    $this->assertSame($payment->description(), $foundPayment->description());
    $this->assertSame($payment->status(), $foundPayment->status());
  }

  public function testItUpdatesExistingPayment(): void
  {
    // Arrange
    $payment = new Payment(
      id: 'pay_repository_test_002',
      amount: 2500,
      currency: 'BRL',
      description: 'Pagamento de integração',
      status: PaymentStatus::PENDING,
    );

    $this->repository->save($payment);

    $updatedPayment = new Payment(
      id: 'pay_repository_test_002',
      amount: 5000,
      currency: 'BRL',
      description: 'Pagamento atualizado',
      status: PaymentStatus::PAID,
    );

    // Act
    $this->repository->save($updatedPayment);

    $foundPayment = $this->repository->findById($updatedPayment->id());

    // Assert
    $this->assertNotNull($foundPayment);
    $this->assertSame($updatedPayment->id(), $foundPayment->id());
    $this->assertSame($updatedPayment->amount(), $foundPayment->amount());
    $this->assertSame($updatedPayment->currency(), $foundPayment->currency());
    $this->assertSame($updatedPayment->description(), $foundPayment->description());
    $this->assertSame($updatedPayment->status(), $foundPayment->status());

    $this->assertSame(
      1,
      PaymentModel::query()
        ->where('id', 'pay_repository_test_002')
        ->count()
    );
  }

  public function testItReturnsNullWhenPaymentDoesNotExist(): void
  {
    // Act
    $payment = $this->repository->findById('pay_payment_that_does_not_exist');

    // Assert
    $this->assertNull($payment);
  }
}
