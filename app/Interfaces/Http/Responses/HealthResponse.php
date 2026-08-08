<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Responses;

use Hyperf\Contract\Arrayable;

final class HealthResponse implements Arrayable, \JsonSerializable
{
  public function __construct(private readonly array $payload) {}

  public function toArray(): array
  {
    return $this->payload;
  }

  public function jsonSerialize(): array
  {
    return $this->toArray();
  }
}
