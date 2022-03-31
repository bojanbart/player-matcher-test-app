<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api;

use Src\PlayerMatcher\Domain\Exceptions\BotInvalidLevelException;
use Src\PlayerMatcher\Domain\Model\Bot;
use Src\PlayerMatcher\Domain\Model\Opponent;
use Src\PlayerMatcher\Domain\Ports\PlayerRepository;

class OpponentFactory
{
    public function __construct(private PlayerRepository $playerRepository)
    {
    }

    public function create(string $name): ?Opponent
    {
        $player = $this->playerRepository->fetchByName($name);

        if ($player !== null)
        {
            return $player;
        }

        try
        {
            return new Bot($name);
        }
        catch (BotInvalidLevelException $e)
        {
            return null;
        }
    }
}