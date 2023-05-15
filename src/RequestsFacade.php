<?php

namespace Summonshr\Requests;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Summonshr\Requests\Skeleton\SkeletonClass
 */
class RequestsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'requests';
    }
}
