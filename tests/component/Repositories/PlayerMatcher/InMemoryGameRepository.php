<?php

declare(strict_types=1);

namespace Test\Component\Repositories\PlayerMatcher;

use Src\PlayerMatcher\Domain\Model\FunGame;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Ports\GameRepository;

class InMemoryGameRepository implements GameRepository
{
    public array $games = [];

    public function fetch(int $id): ?Game
    {
        return $this->games[$id] ?? null;
    }

    public function fetchListToAssign(): array
    {
        return $this->games;
    }

    public function fetchByName(string $name): ?Game
    {
        foreach ($this->games as $game)
        {
            if ($game->getName() === $name)
            {
                return $game;
            }
        }

        return null;
    }

    public function create(GameValueObject $gameData, Player $creator): ?int
    {
        $id = $this->getNextId();

        $this->games[$id] = new FunGame($id, $gameData->getName(), $gameData->getSlots(), $creator);

        return $id;
    }

    public function fetchByCreator(Player $creator): array
    {
        $createdBy = [];

        foreach ($this->games as $game)
        {
            if ($game->getCreator()
                    ->getId() === $creator->getId())
            {
                $createdBy[] = $game;
            }
        }

        return $createdBy;
    }

    public function cancel(Game $game): void
    {
        unset($this->games[$game->getId()]);
    }

    public function update(Game $game): void
    {
        $this->games[$game->getId()] = $game;
    }

    private function getNextId(): int
    {
        $nextId = 1;

        foreach ($this->games as $game)
        {
            if ($game->getId() >= $nextId)
            {
                $nextId = $game->getId() + 1;
            }
        }

        return $nextId;
    }
}