<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacade getFacade()
 */
class FossMarkDownGeneratorConsole extends Console
{

    const COMMAND_NAME = 'foss:mark-down-generate';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Generate FOSS.md');

        $facade = $this->getFacade();

        $facade->writeInstalledPackagesToMarkDownFile(
            $facade->getInstalledPackages()
        );
    }

}
