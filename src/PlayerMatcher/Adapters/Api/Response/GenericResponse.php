<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Response;

use Symfony\Component\HttpFoundation\Response;

trait GenericResponse
{
    public function createInvalidRequestResponse(?string $message): Response
    {
        return new Response($message ?? 'Invalid Request', Response::HTTP_BAD_REQUEST, ['content-type' => 'text/html']);
    }

    public function createNotFoundResponse(?string $message): Response
    {
        return new Response($message ?? 'Not Found', Response::HTTP_NOT_FOUND, ['content-type' => 'text/html']);
    }
}