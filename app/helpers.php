<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

if(!function_exists('getByPatternId')) {
    function getByPatternId($data, $pattern, $replacePattern): Collection
    {
       return Str::of($data['content'])
           ->matchAll($pattern)
           ->unique()
           ->transform(function ($id) use ($replacePattern) {
               return Str::of($id)->replaceMatches($replacePattern, '')->value();
           });
    }
}
