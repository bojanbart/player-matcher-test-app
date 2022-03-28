<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Ports;

use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\Player;

interface GameRepository
{

    public function fetch(int $id): ?Game;

    public function fetchListToAssign(): array;

    public function fetchByName(string $name): ?Game;

    public function create(GameValueObject $gameData,
        Player $creator): ?int;

    public function fetchByCreator(Player $creator): array;

    public function cancel(Game $game): void;

    public function update(Game $game): void;
}