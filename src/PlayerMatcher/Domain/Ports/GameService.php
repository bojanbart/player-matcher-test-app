<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Ports;

use Src\PlayerMatcher\Domain\Exceptions\GameDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\GameNameNotUniqueException;
use Src\PlayerMatcher\Domain\Exceptions\OneActiveGamePerPlayerException;
use Src\PlayerMatcher\Domain\Exceptions\UnauthorizedGameCancellationException;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\Opponent;
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
        if ($game->getCreator()
                ->getId() !== $player->getId())
        {
            throw new UnauthorizedGameCancellationException("Player: {$player->getId()} cannot cancel game: {$game->getId()}");
        }

        $this->gameRepository->cancel($game);
    }

    public function assignPlayer(Game $game, Opponent $toAssign): Game
    {
        $game->assignOpponent($toAssign);

        $this->gameRepository->update($game);

        return $game;
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