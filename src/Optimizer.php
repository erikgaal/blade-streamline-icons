<?php

namespace ErikGaal\BladeStreamlineIcons;

use Symfony\Component\Process\Process;

class Optimizer
{
    public function canOptimize(): bool
    {
        $process = Process::fromShellCommandline('svgo --version');
        $process->run();

        return $process->isSuccessful();
    }

    public function optimize(string $inputPath, ?string $outputPath = null): void
    {
        Process::fromShellCommandline($this->getCommand($inputPath, $outputPath ?? $inputPath))->mustRun();
    }

    private function getCommand(string $inputPath, string $outputPath): string
    {
        return sprintf('svgo --input=%s --output=%s', escapeshellarg($inputPath), escapeshellarg($outputPath));
    }
}
