<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Ports;

use Src\PlayerMatcher\Domain\Exceptions\GameDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\GameNameNotUniqueException;
use Src\PlayerMatcher\Domain\Exceptions\OneActiveGamePerPlayerException;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\Player;

class GameService
{
    public function __construct(private GameRepository $gameRepository)
    {

    }


    public function create(GameValueObject $gameData, Player $creator): Game
    {
        if ($this->gameRepository->fetchByName($gameData->getName()) !== null)
        {
            throw new GameNameNotUniqueException("Game with name: {$gameData->getName()} already exists");
        }

        if (count($this->gameRepository->fetchByCreator($creator)) !== 0)
        {
            throw new OneActiveGamePerPlayerException("There is already at least one game awaiting players created by player: {$creator->getId()}");
        }

        $id = $this->gameRepository->create($gameData, $creator);

        if ($id === null)
        {
            throw new \Exception('Unable to create game');
        }

        $game = $this->gameRepository->fetch($id);

        if ($game === null)
        {
            throw new \Exception('Unable to create game');
        }

        return $game;
    }

    public function cancel(Game $game, Player $player): void
    {

    }

    public function assignPlayer(Game $game, Player $toAssign): Game
    {

    }

    public function get(int $id): Game
    {
        $game = $this->gameRepository->fetch($id);

        if ($game === null)
        {
            throw new GameDoesntExistException("No game with id: {$id}");
        }

        return $game;
    }

    public function list(): array
    {
        return $this->gameRepository->fetchListToAssign();
    }
}