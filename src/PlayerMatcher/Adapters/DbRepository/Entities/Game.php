<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\DbRepository\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Src\PlayerMatcher\Domain\Model\Bot;
use Src\PlayerMatcher\Domain\Model\FunGame;
use Src\PlayerMatcher\Domain\Model\Game as DomainGame;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
 */
class Game
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $slots;

    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="createdGames")
     */
    private Player $creator;

    /**
     * @ORM\OneToMany(targetEntity="Opponent", mappedBy="game", cascade={"persist", "remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     * @var Opponent[]
     */
    private $opponents;

    public function addOpponent(Opponent $opponent): void
    {
        if ($this->opponents === null) {
            $this->opponents = new ArrayCollection();
        }

        $this->opponents->add($opponent);
    }

    public function toDomainModel(): DomainGame
    {
        $game = new FunGame($this->id, $this->name, $this->slots, $this->creator->toDomainModel());

        if($this->opponents === null){
            return $game;
        }

        foreach ($this->opponents as $opponent)
        {
            if ($opponent->isBot())
            {
                $game->assignOpponent(new Bot($opponent->getAiLevel()));
            }
            else
            {
                $game->assignOpponent($opponent->getPlayer()
                    ->toDomainModel());
            }
        }

        return $game;
    }

    public function getMaxOrderValueForOpponent(): int
    {
        if ($this->opponents === null || count($this->opponents) === 0)
        {
            return 0;
        }

        return $this->opponents[count($this->opponents) - 1]->getOrder();
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $slots
     */
    public function setSlots(int $slots): void
    {
        $this->slots = $slots;
    }

    /**
     * @param Player $creator
     */
    public function setCreator(Player $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}