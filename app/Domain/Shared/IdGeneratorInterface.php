<?php

declare(strict_types=1);

namespace App\Domain\Shared;

/**
 * Port interface for generating unique identifiers.
 * 
 * This interface defines the contract that any ID generation service
 * must implement. Different adapters can provide various strategies
 * (UUID, NanoID, custom prefixed IDs, etc.)
 */
interface IdGeneratorInterface
{
  /**
   * Generate a unique identifier with optional prefix.
   *
   * @param string $prefix Optional prefix to prepend to the generated ID
   *
   * @return string A unique identifier
   */
  public function generate(string $prefix = ''): string;
}
