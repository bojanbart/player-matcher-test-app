<?php

declare(strict_types=1);

namespace Test\PlayerMatcher\Domain\Model;

use Src\PlayerMatcher\Domain\Exceptions\AllGameSlotsAssignedException;
use Src\PlayerMatcher\Domain\Exceptions\PlayerAlreadyAssignedToGameException;
use Src\PlayerMatcher\Domain\Model\Bot;
use Src\PlayerMatcher\Domain\Model\FunGame;
use Src\PlayerMatcher\Domain\Model\HumanPlayer;

class GameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @return void
     * @throws \Src\PlayerMatcher\Domain\Exceptions\AllGameSlotsAssignedException
     */
    public function shouldAssignOpponentToGame()
    {
        $game = new FunGame(1, 'game', 4, new HumanPlayer(4, 'creator'));

        $game->assignOpponent(new HumanPlayer(5, 'mark'));
        $game->assignOpponent(new Bot(Bot::WEAK_BOT_LEVEL));

        $this->assertEquals(false, $game->allSlotsAssigned());
        $this->assertEquals(3, count($game->getOpponents()));
    }

    /**
     * @test
     * @return void
     * @throws \Src\PlayerMatcher\Domain\Exceptions\AllGameSlotsAssignedException
     */
    public function shouldKnowWhenAllSlotsAreFilled()
    {
        $game = new FunGame(1, 'game', 4, new HumanPlayer(4, 'creator'));

        $game->assignOpponent(new HumanPlayer(5, 'mark'));
        $game->assignOpponent(new Bot(Bot::WEAK_BOT_LEVEL));
        $game->assignOpponent(new Bot(Bot::MEDIOCRE_BOT_LEVEL));

        $this->assertEquals(true, $game->allSlotsAssigned());
        $this->assertEquals(4, count($game->getOpponents()));
    }

    /**
     * @test
     * @return void
     * @throws AllGameSlotsAssignedException
     */
    public function shouldThrowExceptionWhenAllSlotsAreFilledAntTryingToAssignAnotherOpponent()
    {
        $this->expectException(AllGameSlotsAssignedException::class);

        $game = new FunGame(2, 'game', 2, new HumanPlayer(2, 'creator'));
        $game->assignOpponent(new Bot(Bot::MEDIOCRE_BOT_LEVEL));
        $game->assignOpponent(new Bot(Bot::STRONG_BOT_LEVEL));
    }

    /**
     * @test
     * @return void
     * @throws AllGameSlotsAssignedException
     */
    public function shouldNotAssignPlayerTwiceToSameGame()
    {
        $this->expectException(PlayerAlreadyAssignedToGameException::class);

        $player = new HumanPlayer(1, 'bojan');
        $game   = new FunGame(1, 'game', 4, $player);
        $game->assignOpponent($player);
    }

    /**
     * @test
     * @return void
     * @throws AllGameSlotsAssignedException
     */
    public function shouldBeAbleToAssignMoreThanOneSameBotLevelToGame()
    {
        $game = new FunGame(1, 'game', 4, new HumanPlayer(1, 'creator'));

        $game->assignOpponent(Bot::mediocre());
        $game->assignOpponent(Bot::mediocre());
        $game->assignOpponent(Bot::mediocre());

        $this->assertTrue($game->allSlotsAssigned());

        $mediocreBotCount = 0;

        foreach ($game->getOpponents() as $opponent)
        {
            if ($opponent instanceof Bot && $opponent->getLevel() === Bot::MEDIOCRE_BOT_LEVEL)
            {
                $mediocreBotCount++;
            }
        }

        $this->assertEquals(3, $mediocreBotCount);
    }
}