<?php

declare(strict_types=1);

namespace Test\PlayerMatcher\Domain\Ports;

use PHPUnit\Framework\TestCase;
use Src\PlayerMatcher\Domain\Exceptions\PlayerDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\PlayerNameNotUniqueException;
use Src\PlayerMatcher\Domain\Model\HumanPlayer;
use Src\PlayerMatcher\Domain\Model\Player;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;
use Src\PlayerMatcher\Domain\Ports\PlayerRepository;
use Src\PlayerMatcher\Domain\Ports\PlayerService;

class PlayerServiceTest extends TestCase
{
    private PlayerRepository|\PHPUnit\Framework\MockObject\MockObject $repository;
    private PlayerService                                             $serviceUnderTest;

    public function setUp(): void
    {
        $this->repository       = $this->createMock(PlayerRepository::class);
        $this->serviceUnderTest = new PlayerService($this->repository);
    }

    /**
     * @test
     * @return void
     * @throws \Src\PlayerMatcher\Domain\Exceptions\PlayerNameNotUniqueException
     */
    public function shouldCreateValidPlayer()
    {
        // given
        $playerData = new PlayerValueObject('bojan');

        $this->repository->expects($this->once())
            ->method('create')
            ->with($this->equalTo($playerData))
            ->will($this->returnValue(3));

        $this->repository->expects($this->once())
            ->method('fetchByName')
            ->with($this->equalTo('bojan'))
            ->will($this->returnValue(null));

        $this->repository->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo(3))
            ->will($this->returnValue(new HumanPlayer(3, $playerData->getName())));

        // when
        $player = $this->serviceUnderTest->create($playerData);

        // then
        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals(3, $player->getId());
        $this->assertEquals('bojan', $player->getName());
    }

    /**
     * @test
     * @return void
     * @throws PlayerNameNotUniqueException
     */
    public function shouldThrowExceptionWhenTryingToCreateSamePlayer()
    {
        // exception
        $this->expectException(PlayerNameNotUniqueException::class);
        $this->expectExceptionMessage("Player with name: bojan already exists");

        // given
        $playerData = new PlayerValueObject('bojan');

        $this->repository->expects($this->once())
            ->method('fetchByName')
            ->with($this->equalTo('bojan'))
            ->will($this->returnValue(new HumanPlayer(1, 'bojan')));

        // when
        $this->serviceUnderTest->create($playerData);
    }

    /**
     * @test
     * @return void
     * @throws \Src\PlayerMatcher\Domain\Exceptions\PlayerDoesntExistException
     */
    public function shouldFetchPlayerByIdentifier()
    {
        // given
        $this->repository->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo(4))
            ->will($this->returnValue(new HumanPlayer(4, 'bojan')));

        // when
        $player = $this->serviceUnderTest->get(4);

        // then
        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals(4, $player->getId());
        $this->assertEquals('bojan', $player->getName());
    }

    /**
     * @test
     * @return void
     * @throws PlayerDoesntExistException
     */
    public function shouldThrowExceptionWhenFetchingNonExistingPlayer()
    {
        // expect
        $this->expectException(PlayerDoesntExistException::class);
        $this->expectExceptionMessage('No player with id: 5');

        // given
        $this->repository->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo(5))
            ->will($this->returnValue(null));

        // when
        $this->serviceUnderTest->get(5);
    }
}