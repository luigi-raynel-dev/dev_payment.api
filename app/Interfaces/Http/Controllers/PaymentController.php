<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Application\Payment\CreatePayment;
use App\Application\Payment\CreatePaymentInput;
use App\Controller\AbstractController;
use Hyperf\HttpServer\Contract\RequestInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Payment Controller.
 *
 * Handles HTTP requests related to payment operations.
 * This controller is intentionally thin - it only:
 * 1. Validates HTTP layer concerns (Content-Type, etc.)
 * 2. Extracts data from the request
 * 3. Delegates business logic to the use case
 * 4. Returns appropriate HTTP responses
 *
 * Business logic lives in the Application layer (CreatePayment use case).
 * Data validation lives in the Domain layer (Payment entity).
 */
final class PaymentController extends AbstractController
{
  public function __construct(private readonly CreatePayment $createPaymentUseCase) {}

  /**
   * Create a new payment.
   *
   * POST /payments
   *
   * Request body:
   * {
   *   "amount": 2500,
   *   "currency": "BRL",
   *   "description": "Payment description",
   *   "status": "pending"
   * }
   *
   * Response (201):
   * {
   *   "id": "pay_550e8400-e29b-41d4-a716-446655440000",
   *   "amount": 2500,
   *   "currency": "BRL",
   *   "description": "Payment description",
   *   "status": "pending"
   * }
   *
   * @return ResponseInterface The created payment data
   */
  public function create(RequestInterface $request): ResponseInterface
  {
    try {
      // Create input DTO
      $input = new CreatePaymentInput(
        amount: (int) ($request->input('amount') ?? 0),
        currency: (string) ($request->input('currency') ?? ''),
        description: (string) ($request->input('description') ?? ''),
        status: (string) ($request->input('status') ?? 'pending'),
      );

      // Execute use case
      $output = $this->createPaymentUseCase->execute($input);

      // Return success response with 201 Created
      return $this->success($output, 201);
    } catch (InvalidArgumentException $e) {
      // Domain/Application validation errors
      return $this->fail($e->getMessage(), 422);
    } catch (\Throwable $e) {
      // Unexpected errors
      return $this->fail('Internal server error', 500);
    }
  }

  /**
   * Helper method to return success responses.
   *
   * @param mixed $data The response data
   * @param int $statusCode HTTP status code
   *
   * @return ResponseInterface Formatted response
   */
  private function success(mixed $data, int $statusCode = 200): ResponseInterface
  {
    return $this->response->json([
      'success' => true,
      'data' => $data,
    ])
      ->withStatus($statusCode);
  }

  /**
   * Helper method to return error responses.
   *
   * @param string $message The error message
   * @param int $statusCode HTTP status code
   *
   * @return ResponseInterface Formatted response
   */
  private function fail(string $message, int $statusCode = 400): ResponseInterface
  {
    return $this->response->json([
      'success' => false,
      'error' => $message,
    ])
      ->withStatus($statusCode);
  }
}
