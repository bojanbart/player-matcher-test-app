<?php

namespace Src\PlayerMatcher\Adapters\DbRepository;

use Doctrine\ORM\EntityManager;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;
use Src\PlayerMatcher\Domain\Ports\PlayerRepository as PlayerRepositoryInterface;

class PlayerRepository implements PlayerRepositoryInterface
{

    public function __construct(private EntityManager $entityManager)
    {
    }

    public function fetch(int $id): ?Player
    {
        $player = $this->entityManager->find(Entities\Player::class, $id);

        if ($player === null)
        {
            return null;
        }

        return $player->toDomainModel();
    }

    public function fetchByName(string $name): ?Player
    {
        $player = $this->entityManager->getRepository(Entities\Player::class)
            ->findOneBy(['name' => $name]);

        if ($player === null)
        {
            return null;
        }

        return $player->toDomainModel();
    }

    public function create(PlayerValueObject $playerData): ?int
    {
        $player = new Entities\Player();
        $player->setName($playerData->getName());

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player->getId();
    }
}