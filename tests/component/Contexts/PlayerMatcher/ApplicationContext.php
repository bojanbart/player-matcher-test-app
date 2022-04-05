<?php

declare(strict_types=1);

namespace Test\Component\Contexts\PlayerMatcher;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class ApplicationContext implements Context
{
    /**
     * @Given There is no game with name :arg1
     */
    public function thereIsNoGameWithName($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I am identified as player with id :arg1
     */
    public function iAmIdentifiedAsPlayerWithId($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given There is no active games created by player :arg1
     */
    public function thereIsNoActiveGamesCreatedByPlayer($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I send create game request with name :arg1 and slots :arg2
     */
    public function iSendCreateGameRequestWithNameAndSlots($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then Game data with name :arg1 and slots :arg2 is returned
     */
    public function gameDataWithNameAndSlotsIsReturned($arg1, $arg2)
    {
        throw new PendingException();
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
        throw new PendingException();
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
        throw new PendingException();
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
        throw new PendingException();
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
        throw new PendingException();
    }

    /**
     * @When I send create player request with name :arg1
     */
    public function iSendCreatePlayerRequestWithName($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then Player data with name :arg1 is returned
     */
    public function playerDataWithNameIsReturned($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then Player response contains token
     */
    public function playerResponseContainsToken()
    {
        throw new PendingException();
    }

    /**
     * @When I send get player request with id :arg1
     */
    public function iSendGetPlayerRequestWithId($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given There is no player with id :arg1
     */
    public function thereIsNoPlayerWithId($arg1)
    {
        throw new PendingException();
    }
}