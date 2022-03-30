<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\FileRepository;

use Src\PlayerMatcher\Domain\Model\FunGame;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Ports\GameRepository as GameRepositoryInterface;

class GameRepository implements GameRepositoryInterface
{
    const FILENAME = 'games.json';

    private array $games  = [];
    private bool  $loaded = false;

    private function loadIfNecessary(): void
    {
        if (!$this->loaded)
        {
            $this->load();
            $this->loaded = true;
        }
    }

    private function save(): void
    {
        $gamesToStore = array_map(function (Game $game)
        {
            return \serialize($game);
        }, $this->games);

        file_put_contents(__DIR__ . "/../../../../data/" . self::FILENAME, json_encode($gamesToStore), LOCK_EX);
    }

    private function load(): void
    {
        $this->games = [];

        $file = __DIR__ . "/../../../../data/" . self::FILENAME;

        if (!file_exists($file))
        {
            return;
        }

        $rawArray = json_decode(file_get_contents($file), true);
        foreach ($rawArray as $raw)
        {
            $game = \unserialize($raw);

            $this->games[$game->getId()] = $game;
        }
    }

    public function fetch(int $id): ?Game
    {
        $this->loadIfNecessary();

        return $this->games[$id] ?? null;
    }

    public function fetchListToAssign(): array
    {
        $this->loadIfNecessary();

        return $this->games;
    }

    public function fetchByName(string $name): ?Game
    {
        $this->loadIfNecessary();

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
        $this->loadIfNecessary();

        $newGameId = $this->getNextId();

        $game                    = new FunGame($newGameId, $gameData->getName(), $gameData->getSlots(), $creator);
        $this->games[$newGameId] = $game;

        $this->save();

        return $newGameId;
    }

    public function fetchByCreator(Player $creator): array
    {
        $this->loadIfNecessary();
        $result = [];

        foreach ($this->games as $game)
        {
            if ($game->getCreator()
                    ->getId() === $creator->getId())
            {
                $result[] = $game;
            }
        }

        return $result;
    }

    public function cancel(Game $game): void
    {
        // TODO: Implement cancel() method.
    }

    public function update(Game $game): void
    {
        // TODO: Implement update() method.
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