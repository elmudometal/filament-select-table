<?php

namespace ElmudoDev\FilamentSelectTable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ElmudoDev\FilamentSelectTable\FilamentSelectTable
 */
class FilamentSelectTable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ElmudoDev\FilamentSelectTable\FilamentSelectTable::class;
    }
}
