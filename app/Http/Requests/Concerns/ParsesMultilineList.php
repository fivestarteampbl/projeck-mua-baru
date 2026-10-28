<?php

namespace App\Http\Requests\Concerns;

trait ParsesMultilineList
{
    private function parseMultilineList(?string $text): array
    {
        return collect(explode("\n", $text ?? ''))
            ->map(fn (string $baris) => trim($baris))
            ->filter()
            ->values()
            ->all();
    }
}
