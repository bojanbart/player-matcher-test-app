<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

interface Game
{

    public function assignOpponent(Opponent $opponent): void;

    public function allSlotsAssigned(): bool;

    public function getOpponents(): array;

    public function getId(): int;

    public function getName(): string;

    public function getSlots(): int;

    public function getCreator(): Player;
}