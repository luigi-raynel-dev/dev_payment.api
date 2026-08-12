<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use Hyperf\Testing\TestCase;

final class HealthControllerTest extends TestCase
{
  protected function tearDown(): void
  {
    restore_error_handler();
    restore_exception_handler();

    parent::tearDown();
  }

  public function testHealthEndpointReturnsStructuredPayload(): void
  {
    $this->get('/health')
      ->assertOk()
      ->assertJsonStructure([
        'status',
        'service',
        'version',
        'environment',
        'timestamp',
      ])
      ->assertJsonFragment([
        'status' => 'UP',
        'service' => 'dev-payment-api',
        'version' => '0.2.0',
      ]);
  }
}
