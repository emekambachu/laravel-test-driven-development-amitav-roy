<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\Video\VideoService;

class YoutubeUrlRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     * @throws BindingResolutionException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $videoService = app()->make(VideoService::class);
        if (!$videoService->validateYoutubeUrl($value)) {
            $fail("The $attribute must be a valid YouTube URL.");
        }
    }
}
