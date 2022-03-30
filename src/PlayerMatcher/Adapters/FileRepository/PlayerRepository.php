<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\FileRepository;

use Src\PlayerMatcher\Domain\Model\HumanPlayer;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;
use Src\PlayerMatcher\Domain\Ports\PlayerRepository as PlayerRepositoryInterface;

class PlayerRepository implements PlayerRepositoryInterface
{

    const FILENAME = 'players.json';

    private array $players = [];
    private bool  $loaded  = false;

    public function fetch(int $id): ?Player
    {
        $this->loadIfNecessary();

        return $this->players[$id] ?? null;
    }

    public function fetchByName(string $name): ?Player
    {
        foreach ($this->players as $player)
        {
            if ($player->getName() === $name)
            {
                return $player;
            }
        }

        return null;
    }

    public function create(PlayerValueObject $playerData): ?int
    {
        $this->loadIfNecessary();

        $newPlayerId = $this->getNextId();

        $player                      = new PlayerArrayDecorator(new HumanPlayer($newPlayerId, $playerData->getName()));
        $this->players[$newPlayerId] = $player;

        $this->save();

        return $newPlayerId;
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
        $playersToStore = array_map(function ($raw)
        {
            return $raw->toArray();
        }, $this->players);

        file_put_contents(__DIR__ . "/../../../../data/" . self::FILENAME, json_encode($playersToStore), LOCK_EX);
    }

    public function load(): void
    {
        $this->players = [];

        $file = __DIR__ . "/../../../../data/" . self::FILENAME;

        if (!file_exists($file))
        {
            return;
        }

        $rawArray      = json_decode(file_get_contents($file), true);

        foreach ($rawArray as $raw)
        {
            $player = PlayerArrayDecorator::fromArray($raw);

            $this->players[$player->getId()] = $player;
        }
    }
}