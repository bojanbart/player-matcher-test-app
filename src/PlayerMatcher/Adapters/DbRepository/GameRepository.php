<?php

namespace Src\PlayerMatcher\Adapters\DbRepository;

use Doctrine\ORM\EntityManager;
use Src\PlayerMatcher\Domain\Model\Bot;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\Opponent;
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

        if ($creatorEntity === null)
        {
            throw new \Exception("Unable to fetch player: #{$creator->getId()}");
        }

        $game->setCreator($creatorEntity);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game->getId();
    }

    public function fetchByCreator(Player $creator): array
    {
        $playerEntity = $this->getPlayerEntity($creator->getId());

        $games = $playerEntity->getCreatedGames();

        return array_map(function (Entities\Game $game)
        {
            return $game->toDomainModel();
        }, $games->toArray());
    }

    public function cancel(Game $game): void
    {
        $gameEntity = $this->getGameEntity($game->getId());

        $this->entityManager->remove($gameEntity);
        $this->entityManager->flush();
    }

    private function getPlayerEntity(int $id): Entities\Player
    {
        $playerEntity = $this->entityManager->find(Entities\Player::class, $id);

        if ($playerEntity === null)
        {
            throw new \Exception("Unable to fetch player: #{$id}");
        }

        return $playerEntity;
    }

    private function getGameEntity(int $id): Entities\Game
    {
        $gameEntity = $this->entityManager->find(Entities\Game::class, $id);

        if ($gameEntity === null)
        {
            throw new \Exception("Unable to find game: #{$id}");
        }

        return $gameEntity;
    }

    public function update(Game $game): void
    {
        $gameEntity = $this->getGameEntity($game->getId());

        $updatedOpponents  = $game->getOpponents();
        $existingOpponents = $gameEntity->toDomainModel()
            ->getOpponents();
        $nextOrder         = $gameEntity->getMaxOrderValueForOpponent() + 1;

        foreach ($this->determineOpponentsToAdd($updatedOpponents, $existingOpponents) as $newOpponent)
        {
            $opponentEntity = $this->createNewOpponentEntity($newOpponent);
            $opponentEntity->setOrder($nextOrder);
            $opponentEntity->setGame($gameEntity);
            $gameEntity->addOpponent($opponentEntity);
            $nextOrder++;
        }

        $this->entityManager->flush();
    }

    private function createNewOpponentEntity(Opponent $domainOpponent): Entities\Opponent
    {
        $opponentEntity = new Entities\Opponent();

        if ($domainOpponent instanceof Bot)
        {
            $opponentEntity->setAiLevel($domainOpponent->getLevel());
        }
        else
        {
            $playerEntity = $this->getPlayerEntity($domainOpponent->getId());
            $opponentEntity->setPlayer($playerEntity);
        }

        return $opponentEntity;
    }

    private function determineOpponentsToAdd(array $updated, array $existing): array
    {
        $result = [];

        $existingCount = count($existing);
        $updatedCount  = count($updated);

        for (
            $i = $existingCount; $i < $updatedCount; $i++)
        {
            $result[] = $updated[$i];
        }

        return $result;
    }
}