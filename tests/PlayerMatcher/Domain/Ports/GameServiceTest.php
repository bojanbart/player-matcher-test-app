<?php

declare(strict_types=1);

namespace Test\PlayerMatcher\Domain\Ports;

use Src\PlayerMatcher\Domain\Exceptions\GameDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\GameNameNotUniqueException;
use Src\PlayerMatcher\Domain\Exceptions\OneActiveGamePerPlayerException;
use Src\PlayerMatcher\Domain\Exceptions\UnauthorizedGameCancellationException;
use Src\PlayerMatcher\Domain\Model\FunGame;
use Src\PlayerMatcher\Domain\Model\Game;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Model\HumanPlayer;
use Src\PlayerMatcher\Domain\Ports\GameRepository;
use Src\PlayerMatcher\Domain\Ports\GameService;

class GameServiceTest extends \PHPUnit\Framework\TestCase
{

    private \PHPUnit\Framework\MockObject\MockObject|GameRepository $gameRepository;
    private GameService                                             $serviceUnderTest;

    public function setUp(): void
    {
        $this->gameRepository   = $this->createMock(GameRepository::class);
        $this->serviceUnderTest = new GameService($this->gameRepository);
    }

    /**
     * @test
     * @return void
     * @throws \Src\PlayerMatcher\Domain\Exceptions\GameNameNotUniqueException
     * @throws \Src\PlayerMatcher\Domain\Exceptions\OneActiveGamePerPlayerException
     */
    public function shouldCreateValidGame()
    {
        // given
        $gameData = new GameValueObject('game', 4);
        $creator  = new HumanPlayer(1, 'creator');

        $this->gameRepository->expects($this->once())
            ->method('create')
            ->with($this->equalTo($gameData), $this->equalTo($creator))
            ->will($this->returnValue(2));

        $this->gameRepository->expects($this->once())
            ->method('fetchByName')
            ->with($this->equalTo('game'))
            ->will($this->returnValue(null));

        $this->gameRepository->expects($this->once())
            ->method('fetchByCreator')
            ->with($this->equalTo($creator))
            ->will($this->returnValue([]));

        $this->gameRepository->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo(2))
            ->will($this->returnValue(new FunGame(2, 'game', 4, $creator)));

        // when
        $game = $this->serviceUnderTest->create($gameData, $creator);

        // then
        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals(2, $game->getId());
        $this->assertEquals('game', $game->getName());
        $this->assertEquals(4, $game->getSlots());
        $this->assertEquals($creator, $game->getCreator());
        $this->assertCount(1, $game->getOpponents());
    }

    /**
     * @test
     * @return void
     * @throws \Src\PlayerMatcher\Domain\Exceptions\GameNameNotUniqueException
     * @throws \Src\PlayerMatcher\Domain\Exceptions\OneActiveGamePerPlayerException
     */
    public function shouldThrowExceptionWhenNameIsNotUnique()
    {
        // exception
        $this->expectException(GameNameNotUniqueException::class);

        // given
        $gameData = new GameValueObject('game', 4);
        $creator  = new HumanPlayer(1, 'creator');

        $this->gameRepository->expects($this->never())
            ->method('create');

        $this->gameRepository->expects($this->once())
            ->method('fetchByName')
            ->with($this->equalTo('game'))
            ->will($this->returnValue(new FunGame(4, 'game', 5, new HumanPlayer(1, 'john'))));

        // when
        $this->serviceUnderTest->create($gameData, $creator);
    }

    /**
     * @test
     * @return void
     * @throws GameNameNotUniqueException
     * @throws OneActiveGamePerPlayerException
     */
    public function shouldThrowExceptionWhenThereIsNonCancelledGameCreatedByThisPlayer()
    {
        // exception
        $this->expectException(OneActiveGamePerPlayerException::class);

        // given
        $gameData = new GameValueObject('game', 4);
        $creator  = new HumanPlayer(1, 'creator');

        $this->gameRepository->expects($this->never())
            ->method('create');

        $this->gameRepository->expects($this->once())
            ->method('fetchByName')
            ->with($this->equalTo('game'))
            ->will($this->returnValue(null));

        $this->gameRepository->expects($this->once())
            ->method('fetchByCreator')
            ->with($this->equalTo($creator))
            ->will($this->returnValue([new FunGame(3, 'some game', 2, $creator)]));

        // when
        $this->serviceUnderTest->create($gameData, $creator);
    }

    /**
     * @test
     * @return void
     * @throws UnauthorizedGameCancellationException
     */
    public function authorCanCancelHisGame()
    {
        $creator = new HumanPlayer(1, 'creator');
        $game    = new FunGame(2, 'game', 4, $creator);

        $this->gameRepository->expects($this->once())
            ->method('cancel')
            ->with($this->equalTo($game));

        $this->serviceUnderTest->cancel($game, $creator);
    }

    /**
     * @test
     * @return void
     * @throws UnauthorizedGameCancellationException
     */
    public function gameCannotBeCanceledByOtherPlayer()
    {
        $this->expectException(UnauthorizedGameCancellationException::class);

        $creator = new HumanPlayer(1, 'creator');
        $game    = new FunGame(2, 'game', 4, $creator);

        $this->gameRepository->expects($this->never())
            ->method('cancel');

        $this->serviceUnderTest->cancel($game, new HumanPlayer(3, 'bojan'));
    }

    /**
     * @test
     * @return void
     */
    public function shouldAssignOpponentToGame()
    {
        $creator = new HumanPlayer(1, 'creator');
        $game    = new FunGame(2, 'game', 4, $creator);
        $playerToAssign = new HumanPlayer(5, 'some dude');

        $this->gameRepository->expects($this->once())
            ->method('update');

        $this->serviceUnderTest->assignPlayer($game, $playerToAssign);
    }

    /**
     * @test
     * @return void
     * @throws GameDoesntExistException
     */
    public function shouldFetchGame()
    {
        $this->gameRepository->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo(1))
            ->will($this->returnValue(new FunGame(1, 'game', 2, new HumanPlayer(1, 'creator'))));

        $game = $this->serviceUnderTest->get(1);

        $this->assertInstanceOf(Game::class, $game);
    }

    /**
     * @test
     * @return void
     * @throws GameDoesntExistException
     */
    public function shouldThrowExceptionWhenFetchingNonExistingGame()
    {
        $this->expectException(GameDoesntExistException::class);
        $this->expectExceptionMessage("No game with id: 2");

        $this->gameRepository->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo(2))
            ->will($this->returnValue(null));

        $this->serviceUnderTest->get(2);
    }

    /**
     * @test
     * @return void
     */
    public function shouldFetchGamesList()
    {
        $this->gameRepository->expects($this->once())
            ->method('fetchListToAssign')
            ->will($this->returnValue([]));

        $this->serviceUnderTest->list();
    }
}