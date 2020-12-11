<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Util\TestDox;

use PHPUnit\Util\Color;

class CliTestDoxPrinter extends \PHPUnit\Util\TestDox\CliTestDoxPrinter
{
    protected function colorizeTextBox(string $color, string $buffer): string
    {
        if (!$this->colors) {
            return $buffer;
        }

        $lines   = preg_split('/\r\n|\r|\n/', $buffer);
        $padding = max(array_map('\strlen', $lines));

        $styledLines = [];

        foreach ($lines as $line) {
            $styledLines[] = Color::colorize($color, $line);
        }

        return implode(PHP_EOL, $styledLines);
    }
}
