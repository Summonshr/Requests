<?php

namespace Summonshr\Requests\Controllers;

use Illuminate\Http\Request;

class UniversalController
{
    public function __invoke(Request $request)
    {
        return $request->process();
    }
}
