<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared;

use App\Domain\Shared\IdGeneratorInterface;
use Ramsey\Uuid\Uuid;

/**
 * UUID-based ID generator implementation.
 *
 * Generates universally unique identifiers using UUID v4.
 * This is a concrete adapter that implements the IdGeneratorInterface port.
 */
final class UuidIdGenerator implements IdGeneratorInterface
{
  /**
   * Generate a UUID v4 with optional prefix.
   *
   * @param string $prefix Optional prefix to prepend to the generated ID
   *
   * @return string A unique identifier in the format: prefix_uuid (or just uuid if no prefix)
   */
  public function generate(string $prefix = ''): string
  {
    $uuid = Uuid::uuid4()->toString();

    if ($prefix === '') {
      return $uuid;
    }

    return sprintf('%s_%s', $prefix, $uuid);
  }
}
