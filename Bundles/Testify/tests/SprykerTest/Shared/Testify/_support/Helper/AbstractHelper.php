<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use Symfony\Component\Console\Formatter\OutputFormatter;

class AbstractHelper extends Module
{
    use MessageFormatter;

    /**
     * @var \Symfony\Component\Console\Formatter\OutputFormatter|null
     */
    protected $formatter;

    /**
     * @param string $message
     *
     * @return string
     */
    protected function format(string $message): string
    {
        $formatter = $this->getFormatter();

        return $formatter->format($message);
    }

    /**
     * @return \Symfony\Component\Console\Formatter\OutputFormatter
     */
    protected function getFormatter(): OutputFormatter
    {
        if ($this->formatter === null) {
            $this->formatter = new OutputFormatter(true);
        }

        return $this->formatter;
    }

    /**
     * Prints a message to the console when run in debug mode.
     *
     * @param string $message
     *
     * @return void
     */
    protected function write(string $message): void
    {
        codecept_debug($this->format($message));
    }

    /**
     * Prints a message and a newline to the console when run in debug mode.
     *
     * @param string $message
     *
     * @return void
     */
    protected function writeln(string $message): void
    {
        $this->write($message);
        $this->newline();
    }

    /**
     * Prints a newline to the console when run in debug mode.
     *
     * @return void
     */
    protected function newline(): void
    {
        $this->write("\n");
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function writeMissingHelperMessage(string $message): void
    {
        $this->writeln($message);
    }
}
