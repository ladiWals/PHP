<?php

namespace Hexlet\Php\Runner;

use Tightenco\Support\Collection;

function run()
{
    $collection = collect(['taylor', 'abigail', null])->map(function ($name) {
        return strtoupper($name);
    });

    return $collection;
}
