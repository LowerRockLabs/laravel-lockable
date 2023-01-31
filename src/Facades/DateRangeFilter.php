<?php

namespace LowerRockLabs\LaravelLivewireTablesDateRangeFilter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LowerRockLabs\LaravelLivewireTablesDateRangeFilter\DateRangeFilter
 */
class DateRangeFilter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'daterangefilter';
    }
}
