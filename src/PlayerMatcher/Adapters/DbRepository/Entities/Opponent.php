<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\DbRepository\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="opponents")
 */
class Opponent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="opponents")
     */
    private Game $game;

    /**
     * @ORM\OneToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    private ?Player $player;

    /**
     * @ORM\Column(type="string", nullable="true")
     */
    private ?string $aiLevel;

    /**
     * @ORM\Column(name="order_value", type="integer")
     */
    private int $order;

    public function isBot(): bool {
        return $this->aiLevel !== null && $this->player === null;
    }

    /**
     * @return Player|null
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * @return string|null
     */
    public function getAiLevel(): ?string
    {
        return $this->aiLevel;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param Player|null $player
     */
    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }

    /**
     * @param string|null $aiLevel
     */
    public function setAiLevel(?string $aiLevel): void
    {
        $this->aiLevel = $aiLevel;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * @param Game $game
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }
}