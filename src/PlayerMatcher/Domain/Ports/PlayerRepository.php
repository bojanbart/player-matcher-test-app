<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Ports;

use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;

interface PlayerRepository
{
    public function fetch(int $id): ?Player;

    public function fetchByName(string $name): ?Player;

    public function create(PlayerValueObject $playerData): ?int;
}