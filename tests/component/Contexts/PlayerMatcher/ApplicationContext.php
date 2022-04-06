<?php

declare(strict_types=1);

namespace Test\Component\Contexts\PlayerMatcher;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit\Framework\Assert;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\HumanPlayer;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;
use Src\PlayerMatcher\Domain\Ports\GameService;
use Src\PlayerMatcher\Domain\Ports\PlayerService;
use Test\Component\Repositories\PlayerMatcher\InMemoryGameRepository;
use Test\Component\Repositories\PlayerMatcher\InMemoryPlayerRepository;

class ApplicationContext implements Context
{
    private \Exception|null          $lastException = null;
    private                          $lastResult    = null;
    private PlayerService            $playerService;
    private InMemoryPlayerRepository $playerRepository;
    private GameService              $gameService;
    private InMemoryGameRepository   $gameRepository;
    private HumanPlayer              $identifiedPlayer;

    private function clearLastException(): void
    {
        $this->lastException = null;
    }

    private function clearLastResult(): void
    {
        $this->lastResult = null;
    }

    public function __construct()
    {
        $this->playerRepository = new InMemoryPlayerRepository();
        $this->playerService    = new PlayerService($this->playerRepository);
        $this->gameRepository   = new InMemoryGameRepository();
        $this->gameService      = new GameService($this->gameRepository);
    }

    /**
     * @Given There is no game with name :arg1
     */
    public function thereIsNoGameWithName($arg1)
    {
        $game = $this->gameRepository->fetchByName($arg1);

        if ($game === null)
        {
            return;
        }

        unset($this->gameRepository->games[$game->getId()]);
    }

    /**
     * @Given I am identified as player with id :arg1
     */
    public function iAmIdentifiedAsPlayerWithId($arg1)
    {
        $this->identifiedPlayer = new HumanPlayer((int)$arg1, "Test player");
    }

    /**
     * @Given There is no active games created by player :arg1
     */
    public function thereIsNoActiveGamesCreatedByPlayer($arg1)
    {
        $games = $this->gameRepository->fetchByCreator(new HumanPlayer((int)$arg1, "Test player"));

        foreach ($games as $game)
        {
            unset($this->gameRepository->games[$game->getId()]);
        }
    }

    /**
     * @When I send create game request with name :arg1 and slots :arg2
     */
    public function iSendCreateGameRequestWithNameAndSlots($arg1, $arg2)
    {
        try
        {
            $this->lastResult = $this->gameService->create(new GameValueObject($arg1, (int)$arg2),
                $this->identifiedPlayer);
        }
        catch (\Exception $e)
        {
            $this->lastException = $e;
        }
    }

    /**
     * @Then Game data with name :arg1 and slots :arg2 is returned
     */
    public function gameDataWithNameAndSlotsIsReturned($arg1, $arg2)
    {
        Assert::assertInstanceOf(Game::class, $this->lastResult);
        Assert::assertEquals($arg1, $this->lastResult->getName());
        Assert::assertEquals((int)$arg2, $this->lastResult->getSlots());

        $fromRepository = $this->gameRepository->fetchByName($arg1);
        Assert::assertInstanceOf(Game::class, $fromRepository);
        Assert::assertEquals($arg1, $fromRepository->getName());
        Assert::assertEquals((int)$arg2, $fromRepository->getSlots());
    }

    /**
     * @Given There is game :arg1 with :arg2 slots and id :arg3 created by player :arg4
     */
    public function thereIsGameWithSlotsAndIdCreatedByPlayer($arg1, $arg2, $arg3, $arg4)
    {
        throw new PendingException();
    }

    /**
     * @Then Bad request response is returned
     */
    public function badRequestResponseIsReturned()
    {
        Assert::assertNull($this->lastResult);
        Assert::assertInstanceOf(\Exception::class, $this->lastException);
    }

    /**
     * @Given There is active game created by player :arg1
     */
    public function thereIsActiveGameCreatedByPlayer($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given There is player :arg1 with id :arg2
     */
    public function thereIsPlayerWithId($arg1, $arg2)
    {
        $id = (int)$arg2;

        $this->playerRepository->players[$id] = new HumanPlayer($id, $arg1);
    }

    /**
     * @When I send get game request with id :arg1
     */
    public function iSendGetGameRequestWithId($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then Player :arg1 is present on opponents list
     */
    public function playerIsPresentOnOpponentsList($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given There is no game with id :arg1
     */
    public function thereIsNoGameWithId($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I am not identified
     */
    public function iAmNotIdentified()
    {
        throw new PendingException();
    }

    /**
     * @Then Not found response is returned
     */
    public function notFoundResponseIsReturned()
    {
        Assert::assertNull($this->lastResult);
        Assert::assertInstanceOf(\Exception::class, $this->lastException);
    }

    /**
     * @When I send get game list request
     */
    public function iSendGetGameListRequest()
    {
        throw new PendingException();
    }

    /**
     * @Then Game list is returned containing
     */
    public function gameListIsReturnedContaining()
    {
        throw new PendingException();
    }

    /**
     * @Then Game :arg1 is present on result list
     */
    public function gameIsPresentOnResultList($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I send delete game request with id :arg1
     */
    public function iSendDeleteGameRequestWithId($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then Game should be removed
     */
    public function gameShouldBeRemoved()
    {
        throw new PendingException();
    }

    /**
     * @Then Forbidden response is returned
     */
    public function forbiddenResponseIsReturned()
    {
        throw new PendingException();
    }

    /**
     * @When I send update game :arg1 request with player opponent :arg2
     */
    public function iSendUpdateGameRequestWithPlayerOpponent($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I send update game :arg1 request with ai opponent :arg2
     */
    public function iSendUpdateGameRequestWithAiOpponent($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then Ai bot :arg1 is present on opponents list
     */
    public function aiBotIsPresentOnOpponentsList($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given Game :arg1 has assigned player :arg2
     */
    public function gameHasAssignedPlayer($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Given There is no player with name :arg1
     */
    public function thereIsNoPlayerWithName($arg1)
    {
        $player = $this->playerRepository->fetchByName($arg1);

        if ($player === null)
        {
            return;
        }

        unset($this->playerRepository->players[$player->getId()]);
    }

    /**
     * @When I send create player request with name :arg1
     */
    public function iSendCreatePlayerRequestWithName($arg1)
    {
        $this->clearLastException();
        $this->clearLastResult();

        try
        {
            $this->lastResult = $this->playerService->create(new PlayerValueObject($arg1));
        }
        catch (\Exception $e)
        {
            $this->lastException = $e;
        }
    }

    /**
     * @Then Player data with name :arg1 is returned
     */
    public function playerDataWithNameIsReturned($arg1)
    {
        Assert::assertInstanceOf(Player::class, $this->lastResult);
        Assert::assertEquals($arg1, $this->lastResult->getName());

        $fromRepository = $this->playerRepository->fetchByName($arg1);
        Assert::assertInstanceOf(Player::class, $fromRepository);
        Assert::assertEquals($arg1, $fromRepository->getName());
    }

    /**
     * @Then Player response contains token
     */
    public function playerResponseContainsToken()
    {
        // pass
    }

    /**
     * @When I send get player request with id :arg1
     */
    public function iSendGetPlayerRequestWithId($arg1)
    {
        $this->clearLastException();
        $this->clearLastResult();

        try
        {
            $this->lastResult = $this->playerService->get((int)$arg1);
        }
        catch (\Exception $e)
        {
            $this->lastException = $e;
        }
    }

    /**
     * @Given There is no player with id :arg1
     */
    public function thereIsNoPlayerWithId($arg1)
    {
        unset($this->playerRepository->players[(int)$arg1]);
    }
}