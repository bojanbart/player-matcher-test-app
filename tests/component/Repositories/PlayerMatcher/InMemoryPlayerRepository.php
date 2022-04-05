<?php

declare(strict_types=1);

namespace Test\Component\Repositories\PlayerMatcher;

use Src\PlayerMatcher\Domain\Model\HumanPlayer;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;
use Src\PlayerMatcher\Domain\Ports\PlayerRepository;

class InMemoryPlayerRepository implements PlayerRepository
{
    public array $players = [];

    public function fetch(int $id): ?Player
    {
        return $this->players[$id] ?? null;
    }

    public function fetchByName(string $name): ?Player
    {
        foreach($this->players as $player){
            if($player->getName()===$name){
                return $player;
            }
        }

        return null;
    }

    public function create(PlayerValueObject $playerData): ?int
    {
        $nextId = $this->getNextId();

        $this->players[$nextId] = new HumanPlayer($nextId, $playerData->getName());

        return $nextId;
    }

    private function getNextId(): int
    {
        $nextId = 1;

        foreach ($this->players as $player)
        {
            if ($player->getId() >= $nextId)
            {
                $nextId = $player->getId() + 1;
            }
        }

        return $nextId;
    }
}