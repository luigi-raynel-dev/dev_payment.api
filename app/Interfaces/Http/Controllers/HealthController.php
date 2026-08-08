<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Application\Health\CheckHealthUseCase;
use App\Interfaces\Http\Responses\HealthResponse;
use App\Controller\AbstractController;

final class HealthController extends AbstractController
{
  public function __construct(private readonly CheckHealthUseCase $useCase) {}

  public function index(): HealthResponse
  {
    return new HealthResponse($this->useCase->execute());
  }
}
