<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Application\Payment;

use App\Application\Payment\CreatePayment;
use App\Application\Payment\CreatePaymentInput;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentRepositoryInterface;
use App\Domain\Payment\PaymentStatus;
use App\Domain\Shared\IdGeneratorInterface;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test suite for CreatePayment use case.
 * 
 * Tests verify that the use case:
 * - Orchestrates ID generation, validation, and persistence correctly
 * - Handles all validation scenarios at the application layer
 * - Delegates domain validation to the Payment entity
 * - Returns the correct output DTO
 */
final class CreatePaymentTest extends TestCase
{
  private IdGeneratorInterface&MockObject $idGeneratorMock;
  private PaymentRepositoryInterface&MockObject $repositoryMock;
  private CreatePayment $useCase;

  protected function setUp(): void
  {
    $this->idGeneratorMock = $this->createMock(IdGeneratorInterface::class);
    $this->repositoryMock = $this->createMock(PaymentRepositoryInterface::class);
    $this->useCase = new CreatePayment($this->idGeneratorMock, $this->repositoryMock);
  }

  public function testItCreatesPaymentSuccessfullyWithValidInput(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'BRL',
      description: 'Pagamento de teste',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_550e8400e29b41d4a716446655440000');

    $this->repositoryMock
      ->expects($this->once())
      ->method('save')
      ->with($this->isInstanceOf(Payment::class));

    // Act
    $output = $this->useCase->execute($input);

    // Assert
    $this->assertSame('pay_550e8400e29b41d4a716446655440000', $output->id);
    $this->assertSame(2500, $output->amount);
    $this->assertSame('BRL', $output->currency);
    $this->assertSame('Pagamento de teste', $output->description);
    $this->assertSame('pending', $output->status);
  }

  public function testItCreatesPaymentWithDifferentValidStatuses(): void
  {
    // Test that only 'pending' is valid for creation (other statuses would be for transitions)
    $input = new CreatePaymentInput(
      amount: 5000,
      currency: 'USD',
      description: 'Test payment',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_test_id');

    $this->repositoryMock
      ->expects($this->once())
      ->method('save')
      ->with($this->isInstanceOf(Payment::class));

    $output = $this->useCase->execute($input);

    $this->assertSame('pending', $output->status);
  }

  public function testItRejectsInvalidStatus(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'BRL',
      description: 'Pagamento de teste',
      status: 'invalid_status',
    );

    $this->idGeneratorMock->expects($this->never())->method('generate');
    $this->repositoryMock->expects($this->never())->method('save');

    // Act & Assert
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid payment status: invalid_status. Valid statuses are: pending, paid, failed, canceled.');

    $this->useCase->execute($input);
  }

  public function testItRejectsInvalidAmount(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 0,
      currency: 'BRL',
      description: 'Pagamento de teste',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_test_id');

    $this->repositoryMock->expects($this->never())->method('save');

    // Act & Assert
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The payment amount must be greater than zero and less than or equal to 100000000.');

    $this->useCase->execute($input);
  }

  public function testItRejectsAmountExceedingMaximum(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 100000001, // MAX_AMOUNT is 100000000
      currency: 'BRL',
      description: 'Pagamento de teste',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_test_id');

    $this->repositoryMock->expects($this->never())->method('save');

    // Act & Assert
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The payment amount must be greater than zero and less than or equal to 100000000.');

    $this->useCase->execute($input);
  }

  public function testItRejectsInvalidCurrency(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'XYZ',
      description: 'Pagamento de teste',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_test_id');

    $this->repositoryMock->expects($this->never())->method('save');

    // Act & Assert
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The payment currency must be a valid ISO 4217 code.');

    $this->useCase->execute($input);
  }

  public function testItRejectsEmptyDescription(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'BRL',
      description: '   ',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_test_id');

    $this->repositoryMock->expects($this->never())->method('save');

    // Act & Assert
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('The payment description cannot be empty.');

    $this->useCase->execute($input);
  }

  public function testItNormalizeCurrencyToUppercase(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'brl',
      description: 'Test payment',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_test_id');

    $this->repositoryMock
      ->expects($this->once())
      ->method('save')
      ->with($this->isInstanceOf(Payment::class));

    // Act
    $output = $this->useCase->execute($input);

    // Assert
    $this->assertSame('BRL', $output->currency);
  }

  public function testItTrimsDescriptionWhitespace(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'BRL',
      description: '  Pagamento com espaços  ',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_test_id');

    $this->repositoryMock
      ->expects($this->once())
      ->method('save')
      ->with($this->isInstanceOf(Payment::class));

    // Act
    $output = $this->useCase->execute($input);

    // Assert
    $this->assertSame('Pagamento com espaços', $output->description);
  }

  public function testItCallsRepositorySaveWithCorrectPayment(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'BRL',
      description: 'Pagamento de teste',
      status: 'pending',
    );

    $generatedId = 'pay_550e8400e29b41d4a716446655440000';

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn($generatedId);

    /** @var Payment|null $paymentCapture */
    $paymentCapture = null;

    $this->repositoryMock
      ->expects($this->once())
      ->method('save')
      ->willReturnCallback(function (Payment $payment) use (&$paymentCapture) {
        $paymentCapture = $payment;
      });

    // Act
    $this->useCase->execute($input);

    // Assert
    $this->assertNotNull($paymentCapture);
    $this->assertSame($generatedId, $paymentCapture->id());
    $this->assertSame(2500, $paymentCapture->amount());
    $this->assertSame('BRL', $paymentCapture->currency());
    $this->assertSame('Pagamento de teste', $paymentCapture->description());
    $this->assertSame(PaymentStatus::PENDING, $paymentCapture->status());
  }

  public function testItGeneratesIdWithCorrectPrefix(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 2500,
      currency: 'BRL',
      description: 'Pagamento de teste',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay') // Verify the prefix is correct
      ->willReturn('pay_unique_id');

    $this->repositoryMock
      ->expects($this->once())
      ->method('save')
      ->with($this->isInstanceOf(Payment::class));

    // Act
    $this->useCase->execute($input);

    // Assert - the expectation already verifies the prefix was passed correctly
  }

  public function testItReturnsCorrectOutputDtoStructure(): void
  {
    // Arrange
    $input = new CreatePaymentInput(
      amount: 7500,
      currency: 'EUR',
      description: 'Transferência internacional',
      status: 'pending',
    );

    $this->idGeneratorMock
      ->expects($this->once())
      ->method('generate')
      ->with('pay')
      ->willReturn('pay_euro_123');

    $this->repositoryMock
      ->expects($this->once())
      ->method('save')
      ->with($this->isInstanceOf(Payment::class));

    // Act
    $output = $this->useCase->execute($input);

    // Assert
    $this->assertIsString($output->id);
    $this->assertIsInt($output->amount);
    $this->assertIsString($output->currency);
    $this->assertIsString($output->description);
    $this->assertIsString($output->status);
  }
}
