<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\DbRepository\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Src\PlayerMatcher\Domain\Model\HumanPlayer;
use Src\PlayerMatcher\Domain\Model\Player as DomainPlayer;

/**
 * @ORM\Entity
 * @ORM\Table(name="players")
 */
class Player
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
     * @ORM\OneToMany(targetEntity="Game", mappedBy="creator")
     * @var Game[]
     */
    private $createdGames;

    public function getCreatedGames()
    {
        return $this->createdGames;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function toDomainModel(): DomainPlayer
    {
        return new HumanPlayer($this->id, $this->name);
    }

    public static function fromDomainModel(DomainPlayer $domainPlayer): Player
    {
        $player       = new Player();
        $player->name = $domainPlayer->getName();
        $player->id   = $domainPlayer->getId();

        return $player;
    }
}