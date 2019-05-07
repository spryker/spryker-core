<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Testify\Business\TestifyFacadeInterface getFacade()
 */
class CleanOutputConsole extends Console
{
    protected const COMMAND_NAME = 'testify:clean:output';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Removes all files in test/_output directory.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $removedFiles = $this->getFacade()->cleanUpOutputDirectories();

        if ($output->isVerbose()) {
            $output->writeln(sprintf('Removed "<fg=green>%s</>" files.', count($removedFiles)));
        }

        if ($output->isVeryVerbose()) {
            foreach ($removedFiles as $removedFile) {
                $output->writeln(sprintf('Removed "<fg=yellow>%s</>".', $removedFile));
            }
        }

        return static::CODE_SUCCESS;
    }
}
