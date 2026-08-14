<?php

declare(strict_types=1);

namespace App\Application\Payment;

use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentRepositoryInterface;
use App\Domain\Payment\PaymentStatus;
use App\Domain\Shared\IdGeneratorInterface;
use InvalidArgumentException;

/**
 * Use case for creating a new payment.
 * 
 * This use case orchestrates the creation of a payment entity following the business rules:
 * 1. Generate a unique payment ID using the ID generator port
 * 2. Validate the input (delegated to Payment entity)
 * 3. Create the Payment aggregate with PENDING status
 * 4. Persist the payment through the repository port
 * 5. Return the output DTO
 * 
 * Responsibilities:
 * - Orchestrate ports and domain logic
 * - Validate business rules at application layer
 * - Transform DTOs to domain entities and back
 * 
 * Does NOT handle:
 * - Direct database access (delegated to repository)
 * - HTTP concerns (delegated to controller)
 * - Payment processing logic (part of other use cases)
 */
final class CreatePayment
{
  public function __construct(
    private readonly IdGeneratorInterface $idGenerator,
    private readonly PaymentRepositoryInterface $paymentRepository,
  ) {}

  /**
   * Execute the use case of creating a new payment.
   *
   * @param CreatePaymentInput $input The input data for payment creation
   *
   * @return CreatePaymentOutput The created payment data
   *
   * @throws InvalidArgumentException If validation fails
   */
  public function execute(CreatePaymentInput $input): CreatePaymentOutput
  {
    // Validate input status
    $this->validateStatus($input->status);

    // Generate unique payment ID with prefix
    $paymentId = $this->idGenerator->generate('pay');

    // Convert status string to enum
    $status = PaymentStatus::tryFrom($input->status);
    if ($status === null) {
      throw new InvalidArgumentException(sprintf(
        'Invalid payment status: %s',
        $input->status,
      ));
    }

    // Create the Payment aggregate (domain entity with business rules)
    // The Payment constructor will validate: amount, currency, description
    $payment = new Payment(
      id: $paymentId,
      amount: $input->amount,
      currency: $input->currency,
      description: $input->description,
      status: $status,
    );

    // Persist the payment through the repository port
    $this->paymentRepository->save($payment);

    // Return the output DTO
    return CreatePaymentOutput::fromPayment($payment);
  }

  /**
   * Validate that the provided status is valid.
   *
   * @param string $status The status to validate
   *
   * @throws InvalidArgumentException If the status is invalid
   */
  private function validateStatus(string $status): void
  {
    if (! PaymentStatus::isValid($status)) {
      throw new InvalidArgumentException(sprintf(
        'Invalid payment status: %s. Valid statuses are: pending, paid, failed, canceled.',
        $status,
      ));
    }
  }
}
