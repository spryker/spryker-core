<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class CodeAllSnifferConsole extends Console
{
    protected const COMMAND_NAME = 'code:sniff:all';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Run checks for validation code base.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getProcessCollection() as $process) {
            $exitCode = $process->run(function ($type, $buffer) {
                echo $buffer;
            });

            if ($exitCode > 0) {
                return $exitCode;
            }
        }

        return 0;
    }

    /**
     * @return \Symfony\Component\Process\Process[]
     */
    protected function getProcessCollection(): array
    {
        return [
            $this->createProcess('vendor/bin/console dev:ide:generate-auto-completion'),
            $this->createProcess('vendor/bin/phpstan analyze -c phpstan.neon src/ -l 4'),
            $this->createProcess('vendor/bin/console propel:schema:validate'),
            $this->createProcess('vendor/bin/console propel:schema:validate-xml-names'),
            $this->createProcess('vendor/bin/console transfer:validate'),
            $this->createProcess('vendor/bin/console code:sniff:style'),
            $this->createProcess('vendor/bin/phpmd src/ text vendor/spryker/architecture-sniffer/src/ruleset.xml --minimumpriority 2'),
        ];
    }

    /**
     * @param string $string
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function createProcess(string $string): Process
    {
        return new Process(
            $string,
            null,
            null,
            4800,
            0
        );
    }
}
