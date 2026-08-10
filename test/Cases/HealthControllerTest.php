<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use Hyperf\Testing\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
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
        'checks' => ['database', 'redis'],
      ]);
  }
}
