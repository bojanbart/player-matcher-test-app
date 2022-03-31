<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Response;

use Symfony\Component\HttpFoundation\Response;

trait GenericResponse
{
    public function createForbiddenRequestResponse(?string $message = null): Response
    {
        return new Response($message ?? 'Forbidden Request', Response::HTTP_FORBIDDEN, ['content-type' => 'text/html']);
    }

    public function createInvalidRequestResponse(?string $message = null): Response
    {
        return new Response($message ?? 'Invalid Request', Response::HTTP_BAD_REQUEST, ['content-type' => 'text/html']);
    }

    public function createNotFoundResponse(?string $message = null): Response
    {
        return new Response($message ?? 'Not Found', Response::HTTP_NOT_FOUND, ['content-type' => 'text/html']);
    }

    public function createUnauthorizedResponse(?string $message = null): Response
    {
        return new Response($message ?? 'Unauthorized', Response::HTTP_UNAUTHORIZED, ['content-type' => 'text/html']);
    }
}