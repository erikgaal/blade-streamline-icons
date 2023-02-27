<?php

namespace ErikGaal\BladeStreamlineIcons\Solutions\SolutionProviders;

use BladeUI\Icons\Exceptions\SvgNotFound;
use ErikGaal\BladeStreamlineIcons\Solutions\DownloadIconSolution;
use Illuminate\Support\Str;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Throwable;

class StreamlineBladeIconSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        if (! $exception = $this->getPreviousSvgException($throwable)) {
            return false;
        }

        return $this->getIconNameFromMessage($exception->getMessage());
    }

    public function getSolutions(Throwable $throwable): array
    {
        $exception = $this->getPreviousSvgException($throwable);

        return [
            new DownloadIconSolution($this->getIconNameFromMessage($exception)),
        ];
    }

    private function getIconNameFromMessage(string $message): ?string
    {
        return Str::match('/Svg by name "([A-Za-z0-9_-]+)" from set "streamline" not found./', $message) ?: null;
    }

    private function getPreviousSvgException(Throwable $throwable): ?SvgNotFound
    {
        do {
            if ($throwable instanceof SvgNotFound) {
                return $throwable;
            }
        } while ($throwable = $throwable->getPrevious());

        return null;
    }
}
