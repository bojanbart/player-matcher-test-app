<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Response;

use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\Opponent;
use Symfony\Component\HttpFoundation\Response;

class GameResponseFactory
{
    use GenericResponse;

    private function gameForRes(Game $game): array
    {
        return [
            'id'        => $game->getId(),
            'name'      => $game->getName(),
            'slots'     => $game->getSlots(),
            'opponents' => array_map(function (Opponent $opponent)
            {
                return $opponent->toArray();
            }, $game->getOpponents())
        ];
    }

    public function create(Game $game, int $status = Response::HTTP_OK): Response
    {
        return new Response(json_encode($this->gameForRes($game)), $status, ['Content-Type' => 'application/json']);
    }

    public function createList(array $games): Response
    {
        $list = [];

        foreach ($games as $game)
        {
            $list[] = $this->gameForRes($game);
        }

        return new Response(json_encode($list), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}