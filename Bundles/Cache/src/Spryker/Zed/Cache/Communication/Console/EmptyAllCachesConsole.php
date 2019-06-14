<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Cache\Business\CacheFacadeInterface getFacade()
 * @method \Spryker\Zed\Cache\Communication\CacheCommunicationFactory getFactory()
 */
class EmptyAllCachesConsole extends Console
{
    public const COMMAND_NAME = 'cache:empty-all';

    /**
     * @return void
     */
    public function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Remove all contents from cache directories');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->emptyCache($output);
        $this->emptyAutoLoadCache($output);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function emptyCache(OutputInterface $output)
    {
        $emptiedDirectories = $this->getFacade()->emptyCache();

        $this->info('Removed cache files', true);
        $this->printEmptiedDirectories($emptiedDirectories, $output);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function emptyAutoLoadCache(OutputInterface $output)
    {
        $emptiedDirectories = $this->getFacade()->emptyAutoLoaderCache();

        $this->info('Removed auto-load cache files', true);
        $this->printEmptiedDirectories($emptiedDirectories, $output);
    }

    /**
     * @param string[] $directories
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printEmptiedDirectories(array $directories, OutputInterface $output)
    {
        foreach ($directories as $directory) {
            $output->writeln($directory, OutputInterface::VERBOSITY_NORMAL);
        }
    }
}
