<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api;

use Src\PlayerMatcher\Adapters\Api\Response\GameResponseFactory;
use Src\PlayerMatcher\Domain\Exceptions\GameDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\GameNameNotUniqueException;
use Src\PlayerMatcher\Domain\Exceptions\OneActiveGamePerPlayerException;
use Src\PlayerMatcher\Domain\Exceptions\PlayerDoesntExistException;
use Src\PlayerMatcher\Domain\Exceptions\UnauthorizedGameCancellationException;
use Src\PlayerMatcher\Domain\Model\GameValueObject;
use Src\PlayerMatcher\Domain\Ports\GameService;
use Src\PlayerMatcher\Domain\Ports\PlayerService;
use Symfony\Component\HttpFoundation\Response;

class GameApiService
{
    public function __construct(private GameService $gameService, private PlayerService $playerService, private GameResponseFactory $responseFactory){

    }

    public function create(string $name, int $slots, int $creator): Response{
        $player = $this->playerService->get($creator);
        try
        {
            $game = $this->gameService->create(new GameValueObject($name, $slots), $player);
        }
        catch (GameNameNotUniqueException $e)
        {
            return $this->responseFactory->createInvalidRequestResponse($e->getMessage());
        }
        catch (OneActiveGamePerPlayerException $e)
        {
            return $this->responseFactory->createInvalidRequestResponse($e->getMessage());
        }

        return $this->responseFactory->create($game);
    }

    public function get(int $id): Response {
        try
        {
            $game = $this->gameService->get($id);
        }
        catch (GameDoesntExistException $e)
        {
            return $this->responseFactory->createNotFoundResponse($e->getMessage());
        }

        return $this->responseFactory->create($game);
    }

    public function list(): Response {
        return $this->responseFactory->createList($this->gameService->list());
    }

    public function remove(int $id, int $playerId): Response {
        try
        {
            $this->gameService->cancel($this->gameService->get($id), $this->playerService->get($playerId));
        }
        catch (GameDoesntExistException $e)
        {
            return $this->responseFactory->createInvalidRequestResponse($e->getMessage());
        }
        catch (PlayerDoesntExistException $e)
        {
            throw $e; // player must exist because of token authorization
        }
        catch (UnauthorizedGameCancellationException $e)
        {
            return $this->responseFactory->createInvalidRequestResponse($e->getMessage());
        }

        return new Response('OK', Response::HTTP_ACCEPTED, ['content-type' => 'text/html']);
    }
}