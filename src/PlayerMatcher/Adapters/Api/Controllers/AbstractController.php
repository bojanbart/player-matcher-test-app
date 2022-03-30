<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Controllers;

use Src\PlayerMatcher\Adapters\Api\Model\Token;
use Src\PlayerMatcher\Adapters\Api\Response\GenericResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController
{
    use GenericResponse;

    protected Request $request;

    private ?array $parameters = null;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    protected function getParameters(): array
    {
        if ($this->parameters === null)
        {
            $this->parameters = json_decode($this->request->getContent(), true);
        }

        return $this->parameters;
    }

    public function authorize(): int
    {
        $tokenRaw = $this->request->headers->get('token');

        if ($tokenRaw === null)
        {
            $this->createUnauthorizedResponse()
                ->send();
            exit;
        }

        $token = new Token($tokenRaw);

        if (!$token->isValid())
        {
            $this->createUnauthorizedResponse()
                ->send();
            exit;
        }

        return (int)$token->getPayload()['id'];
    }
}