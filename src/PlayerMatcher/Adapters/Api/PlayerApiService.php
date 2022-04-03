<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api;

use Src\PlayerMatcher\Adapters\Api\Model\Token;
use Src\PlayerMatcher\Adapters\Api\Response\PlayerResponseFactory;
use Src\PlayerMatcher\Domain\Exceptions\PlayerDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\PlayerNameNotUniqueException;
use Src\PlayerMatcher\Domain\Model\PlayerValueObject;
use Src\PlayerMatcher\Domain\Ports\PlayerService;
use Symfony\Component\HttpFoundation\Response;

class PlayerApiService
{

    public function __construct(private PlayerService $playerService,
        private PlayerResponseFactory $responseFactory)
    {
    }

    public function create(string $name): Response
    {
        try
        {
            $player = $this->playerService->create(new PlayerValueObject($name));
        }
        catch (PlayerNameNotUniqueException $e)
        {
            return $this->responseFactory->createInvalidRequestResponse($e->getMessage());
        }

        $token = Token::createNew([
            'id'   => $player->getId(),
            'name' => $player->getName(),
        ]);

        return $this->responseFactory->create($player, $token, Response::HTTP_CREATED);
    }

    public function get(int $id): Response
    {
        try
        {
            $player = $this->playerService->get($id);
        }
        catch (PlayerDoesntExistException $e)
        {
            return $this->responseFactory->createNotFoundResponse($e->getMessage());
        }

        $token = Token::createNew([
            'id'   => $player->getId(),
            'name' => $player->getName(),
        ]);

        return $this->responseFactory->create($player, $token, Response::HTTP_OK);
    }
}