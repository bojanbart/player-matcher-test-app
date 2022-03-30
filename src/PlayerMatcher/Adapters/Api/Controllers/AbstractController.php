<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Controllers;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }
}