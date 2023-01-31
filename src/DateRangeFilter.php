<?php

namespace LowerRockLabs\LaravelLivewireTablesDateRangeFilter;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DateRangeFilter extends Filter
{
    public function validate($value)
    {
        $dateRange = explode(' ', $value);
        if (DateTime::createFromFormat('Y-m-d', $dateRange[0]) === false) {
            return false;
        }
        $this->options['defaultStartDate'] = 'test';

        if (count($dateRange) >= 2) {
            if (DateTime::createFromFormat('Y-m-d', $dateRange[2]) === false) {
                return false;
            }
            $this->options['defaultEndDate'] = $dateRange[2];
        }

        return $value;
    }

    public function isEmpty($value): bool
    {
        return $value === '';
    }

    public function render(DataTableComponent $component)
    {
        return view('components.tables.daterange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
