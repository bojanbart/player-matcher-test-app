<?php

namespace Src\PlayerMatcher\Adapters\DbRepository;

use Doctrine\ORM\EntityManager;
use Src\PlayerMatcher\Adapters\DbRepository\Entities\Opponent;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Ports\GameRepository as GameRepositoryInterface;

class GameRepository implements GameRepositoryInterface
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function fetch(int $id): ?Game
    {
        $game = $this->entityManager->find(Entities\Game::class, $id);

        if ($game === null)
        {
            return null;
        }

        return $game->toDomainModel();
    }

    public function fetchListToAssign(): array
    {
        $games = $this->entityManager->getRepository(Entities\Game::class)
            ->findAll();

        return array_map(function (Entities\Game $game)
        {
            return $game->toDomainModel();
        }, $games);
    }

    public function fetchByName(string $name): ?Game
    {
        $game = $this->entityManager->getRepository(Entities\Game::class)
            ->findOneBy(['name' => $name]);

        if ($game === null)
        {
            return null;
        }

        return $game->toDomainModel();
    }

    public function create(GameValueObject $gameData, Player $creator): ?int
    {
        $game = new Entities\Game();
        $game->setName($gameData->getName());
        $game->setSlots($gameData->getSlots());
        $creatorEntity = $this->entityManager->find(Entities\Player::class, $creator->getId());

        if($creatorEntity === null){
            throw new \Exception("Unable to fetch player: #{$creator->getId()}");
        }

        $game->setCreator($creatorEntity);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game->getId();
    }

    public function fetchByCreator(Player $creator): array
    {
        $player = $this->entityManager->find(Entities\Player::class, $creator->getId());

        if($player === null){
            throw new \Exception("Unable to fetch player: #{$creator->getId()}");
        }

        $games = $player->getCreatedGames();

        return array_map(function (Entities\Game $game)
        {
            return $game->toDomainModel();
        }, $games->toArray());
    }

    public function cancel(Game $game): void
    {
        $gameEntity = $this->entityManager->find(Entities\Game::class, $game->getId());

        if ($gameEntity === null)
        {
            throw new \Exception("Unable to find game: #{$game->getId()}");
        }

        $this->entityManager->remove($gameEntity);
        $this->entityManager->flush();
    }

    public function update(Game $game): void
    {
        // TODO: Implement update() method.
    }
}