<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

if (! function_exists('getByPatternId')) {
    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, string>
     */
    function getByPatternId(array $data, string $pattern, string $replacePattern): Collection
    {
        return Str::of($data['content'])
            ->matchAll($pattern)
            ->unique()
            ->transform(function ($id) use ($replacePattern) {
                return Str::of($id)->replaceMatches($replacePattern, '')->value();
            });
    }
}
