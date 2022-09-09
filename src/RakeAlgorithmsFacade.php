<?php

namespace Doloan09\RakeAlgorithms;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Doloan09\RakeAlgorithms\Skeleton\SkeletonClass
 */
class RakeAlgorithmsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rake_algorithms';
    }
}
