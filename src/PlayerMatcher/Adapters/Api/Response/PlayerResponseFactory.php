<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Response;

use Src\PlayerMatcher\Adapters\Api\Model\Token;
use Src\PlayerMatcher\Domain\Model\Player;
use Symfony\Component\HttpFoundation\Response;

class PlayerResponseFactory
{
    use GenericResponse;

    public function create(Player $player, Token $token, int $status = Response::HTTP_OK): Response
    {
        return new Response(json_encode([
            'token' => $token->getToken(),
            'id'    => $player->getId(),
            'name'  => $player->getName(),
        ]), $status, ['Content-Type' => 'application/json']);
    }
}