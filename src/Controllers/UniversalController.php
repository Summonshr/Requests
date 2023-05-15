<?php

namespace Summonshr\Requests\Controllers;

use Summonshr\Requests\Contracts\UniversalRequestInterface;

class UniversalController
{
    public function __invoke(UniversalRequestInterface $request)
    {
        return $request->process();
    }
}
