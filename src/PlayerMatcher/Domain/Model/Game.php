<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

class Game
{
    /**
     * @var Player[]
     */
    private array $opponents;

    public function __construct(private int $id, private string $name, private int $slots, private Player $creator)
    {
        $this->opponents = [$this->creator];
    }

    public function assignOpponent(Opponent $opponent): void
    {
        $this->opponents[] = $opponent;
    }

    public function allSlotsAssigned(): bool
    {
        return count($this->opponents) === $this->slots;
    }

    /**
     * @return Player[]
     */
    public function getOpponents(): array
    {
        return $this->opponents;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getSlots(): int
    {
        return $this->slots;
    }

    /**
     * @return Player
     */
    public function getCreator(): Player
    {
        return $this->creator;
    }
}