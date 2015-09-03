<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Cache\Communication\Console;

use SprykerFeature\Zed\Cache\Business\CacheFacade;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method CacheFacade getFacade()
 */
class DeleteAllCachesConsole extends Console
{

    const COMMAND_NAME = 'cache:delete-all';
    const DESCRIPTION = 'Deletes all cache files from /data/{Store}/cache for all stores';

    /**
     *
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirs = $this->getFacade()->deleteAllFiles();
        $this->info('Removed cache files', true);
        foreach ($dirs as $dir) {
            $output->writeln($dir);
        }
    }

}
