<?php

declare(strict_types=1);

namespace Test\Unit\PlayerMatcher\Domain\Model;

use Src\PlayerMatcher\Domain\Exceptions\BotInvalidLevelException;
use Src\PlayerMatcher\Domain\Model\Bot;

class BotTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @param string $level
     * @return void
     * @throws BotInvalidLevelException
     * @dataProvider provider
     */
    public function shouldCreateValidBot(string $level)
    {
        $bot = new Bot($level);

        $this->assertEquals($level, $bot->getLevel());
    }

    public function provider(): array
    {
        return [
            [Bot::WEAK_BOT_LEVEL],
            [Bot::MEDIOCRE_BOT_LEVEL],
            [Bot::STRONG_BOT_LEVEL],
        ];
    }

    /**
     * @test
     * @return void
     */
    public function shouldThrowExceptionForInvalidBotLevel()
    {
        $this->expectException(BotInvalidLevelException::class);
        $this->expectDeprecationMessage("Unknown bot level: invalid");

        new Bot('invalid');
    }

}