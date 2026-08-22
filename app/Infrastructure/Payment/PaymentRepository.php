<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment;

use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentRepositoryInterface;
use App\Domain\Payment\PaymentStatus;
use App\Model\Payment as PaymentModel;
use InvalidArgumentException;

/**
 * Concrete implementation of PaymentRepositoryInterface.
 *
 * This class is responsible for:
 * - Mapping domain entities to/from database models
 * - Performing CRUD operations through the ORM
 * - Translating database data to domain entities
 *
 * This is an adapter in the Infrastructure layer - it should contain
 * NO business logic, only data mapping and persistence concerns.
 */
final class PaymentRepository implements PaymentRepositoryInterface
{
  /**
   * Save a payment entity to the database.
   *
   * @param Payment $payment The payment domain entity
   *
   * @throws InvalidArgumentException If saving fails
   */
  public function save(Payment $payment): void
  {
    $paymentModel = PaymentModel::updateOrCreate(
      ['id' => $payment->id()],
      [
        'amount' => $payment->amount(),
        'currency' => $payment->currency(),
        'description' => $payment->description(),
        'status' => $payment->status()->value,
      ],
    );

    if (! $paymentModel->wasRecentlyCreated && ! $paymentModel->isDirty()) {
      // Already exists and nothing changed, still valid
      return;
    }

    if (! $paymentModel->exists) {
      throw new InvalidArgumentException('Failed to save payment to database');
    }
  }

  /**
   * Find a payment by its ID.
   *
   * @param string $id The payment ID
   *
   * @return Payment|null The payment entity or null if not found
   */
  public function findById(string $id): ?Payment
  {
    $paymentModel = PaymentModel::find($id);

    if ($paymentModel === null) {
      return null;
    }

    return $this->mapModelToEntity($paymentModel);
  }

  /**
   * Map a database model to a domain entity.
   *
   * This private method encapsulates the mapping logic,
   * ensuring the domain entity is created with the correct data types and state.
   *
   * @param PaymentModel $model The Eloquent model instance
   *
   * @return Payment The domain entity
   *
   * @throws InvalidArgumentException If the model data is invalid
   */
  private function mapModelToEntity(PaymentModel $model): Payment
  {
    $status = PaymentStatus::tryFrom($model->status);

    if ($status === null) {
      throw new InvalidArgumentException(sprintf(
        'Invalid payment status from database: %s',
        $model->status,
      ));
    }

    return new Payment(
      id: $model->id,
      amount: $model->amount,
      currency: $model->currency,
      description: $model->description,
      status: $status,
    );
  }
}
