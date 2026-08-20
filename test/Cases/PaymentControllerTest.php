<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use App\Model\Payment as PaymentModel;
use Hyperf\Testing\TestCase;

final class PaymentControllerTest extends TestCase
{
  private array $createdPaymentIds = [];

  protected function tearDown(): void
  {
    PaymentModel::query()
      ->whereIn('id', $this->createdPaymentIds)
      ->delete();

    restore_error_handler();
    restore_exception_handler();

    parent::tearDown();
  }

  public function testItCreatesPaymentThroughHttpEndpoint(): void
  {
    // Arrange
    $payload = [
      'amount' => 2500,
      'currency' => 'BRL',
      'description' => 'Pagamento HTTP de integração',
      'status' => 'pending',
    ];

    // Act
    $response = $this->post('/payments', $payload);

    // Assert
    $response
      ->assertCreated()
      ->assertJsonStructure([
        'success',
        'data' => [
          'id',
          'amount',
          'currency',
          'description',
          'status',
        ],
      ])
      ->assertJsonFragment([
        'success' => true,
        'amount' => 2500,
        'currency' => 'BRL',
        'description' => 'Pagamento HTTP de integração',
        'status' => 'pending',
      ]);

    $responseData = $response->json();

    if (isset($responseData['data']['id'])) {
      $this->createdPaymentIds[] = $responseData['data']['id'];
    }

    $this->assertNotEmpty($responseData['data']['id']);

    $this->assertDatabaseHas('payments', [
      'id' => $responseData['data']['id'],
      'amount' => 2500,
      'currency' => 'BRL',
      'description' => 'Pagamento HTTP de integração',
      'status' => 'pending',
    ]);
  }

  public function testItRejectsInvalidPaymentDataWithMinimumAmount(): void
  {
    // Arrange
    $payload = [
      'amount' => 0,
      'currency' => 'BRL',
      'description' => 'Pagamento inválido',
      'status' => 'pending',
    ];

    // Act
    $response = $this->post('/payments', $payload);

    // Assert
    $response
      ->assertStatus(422)
      ->assertJson([
        'success' => false,
        'error' => 'The payment amount must be greater than zero and less than or equal to 100000000.',
      ]);
  }

  public function testItRejectsInvalidPaymentDataWithMaximumAmount(): void
  {
    // Arrange
    $payload = [
      'amount' => 100000001,
      'currency' => 'BRL',
      'description' => 'Pagamento inválido',
      'status' => 'pending',
    ];

    // Act
    $response = $this->post('/payments', $payload);

    // Assert
    $response
      ->assertStatus(422)
      ->assertJson([
        'success' => false,
        'error' => 'The payment amount must be greater than zero and less than or equal to 100000000.',
      ]);
  }

  public function testItRejectsInvalidPaymentDataWithInvalidCurrency(): void
  {
    // Arrange
    $payload = [
      'amount' => 2500,
      'currency' => 'INVALID',
      'description' => 'Pagamento inválido',
      'status' => 'pending',
    ];

    // Act
    $response = $this->post('/payments', $payload);

    // Assert
    $response
      ->assertStatus(422)
      ->assertJson([
        'success' => false,
        'error' => 'The payment currency must be a valid ISO 4217 code.',
      ]);
  }

  public function testItRejectsInvalidPaymentDataWithInvalidStatus(): void
  {
    // Arrange
    $payload = [
      'amount' => 2500,
      'currency' => 'BRL',
      'description' => 'Pagamento inválido',
      'status' => 'invalid_status',
    ];

    // Act
    $response = $this->post('/payments', $payload);

    // Assert
    $response
      ->assertStatus(422)
      ->assertJson([
        'success' => false,
        'error' => 'Invalid payment status: invalid_status. Valid statuses are: pending, paid, failed, canceled.',
      ]);
  }

  public function testItRejectsInvalidPaymentDataWithEmptyDescription(): void
  {
    // Arrange
    $payload = [
      'amount' => 2500,
      'currency' => 'BRL',
      'description' => ' ',
      'status' => 'pending',
    ];

    // Act
    $response = $this->post('/payments', $payload);

    // Assert
    $response
      ->assertStatus(422)
      ->assertJson([
        'success' => false,
        'error' => 'The payment description cannot be empty.',
      ]);
  }
}
