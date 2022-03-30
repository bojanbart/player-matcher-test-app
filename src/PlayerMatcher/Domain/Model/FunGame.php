<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

use Src\PlayerMatcher\Domain\Exceptions\AllGameSlotsAssignedException;
use Src\PlayerMatcher\Domain\Exceptions\PlayerAlreadyAssignedToGameException;

class FunGame implements Game
{
    /**
     * @var Opponent[]
     */
    private array $opponents;

    public function __construct(private int $id, private string $name, private int $slots, private Player $creator)
    {
        $this->opponents = [$this->creator];
    }

    private function checkIfPlayerIsAlreadyAssigned(Player $player): void
    {
        foreach ($this->getOpponents() as $existingOpponent)
        {
            if ($existingOpponent instanceof Player && $existingOpponent->getId() === $player->getId())
            {
                throw new PlayerAlreadyAssignedToGameException("Player: {$player->getId()}");
            }
        }
    }

    public function assignOpponent(Opponent $opponent): void
    {
        if ($this->allSlotsAssigned())
        {
            throw new AllGameSlotsAssignedException();
        }

        if ($opponent instanceof Player)
        {
            $this->checkIfPlayerIsAlreadyAssigned($opponent);
        }

        $this->opponents[] = $opponent;
    }

    public function allSlotsAssigned(): bool
    {
        return count($this->opponents) === $this->slots;
    }

    /**
     * @return Opponent[]
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