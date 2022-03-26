<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Ports;

use Src\PlayerMatcher\Domain\Exceptions\PlayerDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\PlayerNameNotUniqueException;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;

class PlayerService
{
    public function __construct(private PlayerRepository $repository)
    {
    }

    public function create(PlayerValueObject $playerData): Player
    {
        if ($this->repository->fetchByName($playerData->getName()) !== null)
        {
            throw new PlayerNameNotUniqueException("Player with name: {$playerData->getName()} already exists");
        }

        $id = $this->repository->create($playerData);

        if ($id === null)
        {
            throw new \Exception('Unable to create player');
        }

        $player = $this->repository->fetch($id);

        if ($player === null)
        {
            throw new \Exception('Unable to create player');
        }

        return $player;
    }

    public function get(int $id): Player
    {
        $player = $this->repository->fetch($id);

        if ($player === null)
        {
            throw new PlayerDoesntExistException("No player with id: {$id}");
        }

        return $player;
    }
}