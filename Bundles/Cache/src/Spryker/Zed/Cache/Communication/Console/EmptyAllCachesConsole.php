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
        $this->emptyCodeBucketCache($output);
        $this->emptyDefaultCodeBucketCache($output);
        $this->emptyProjectSpecificCache($output);
        $this->emptyAutoLoadCache($output);

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function emptyCodeBucketCache(OutputInterface $output): void
    {
        if (APPLICATION_CODE_BUCKET === '') {
            return;
        }

        $emptiedDirectories = $this->getFacade()->emptyCodeBucketCache();

        $this->info('Removed cache files', true);
        $output->writeln($emptiedDirectories);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function emptyDefaultCodeBucketCache(OutputInterface $output): void
    {
        $emptiedDirectories = $this->getFacade()->emptyDefaultCodeBucketCache();

        $this->info('Removed cache files', true);
        $output->writeln($emptiedDirectories);
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
        $output->writeln($emptiedDirectories);
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
        $output->writeln($emptiedDirectories);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function emptyProjectSpecificCache(OutputInterface $output): void
    {
        $emptiedDirectories = $this->getFacade()->emptyProjectSpecificCache();

        $this->info('Removed project specific cache files', true);
        $output->writeln($emptiedDirectories);
    }
}
