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
 * @deprecated Use Spryker\Zed\Cache\Communication\Console\EmptyAllCachesConsole instead.
 *
 * @method \Spryker\Zed\Cache\Business\CacheFacadeInterface getFacade()
 */
class DeleteAllCachesConsole extends Console
{
    public const COMMAND_NAME = 'cache:delete-all';
    public const DESCRIPTION = 'Deletes all cache files from /data/{Store}/cache for all stores';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirs = $this->getFacade()->deleteAllFiles();
        $this->info('Removed cache files', true);
        $this->displayDeleted($dirs, $output);

        $dirs = $this->getFacade()->deleteAllAutoloaderFiles();
        $this->info('Removed autoloader cache files', true);
        $this->displayDeleted($dirs, $output);
    }

    /**
     * @param array $dirs
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function displayDeleted(array $dirs, OutputInterface $output)
    {
        foreach ($dirs as $dir) {
            $output->writeln($dir);
        }
    }
}
