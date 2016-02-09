<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerFacade getFacade()
 */
class IcecatDataInstallConsole extends Console
{

    const COMMAND_NAME = 'setup:install-icecat-data';
    const DESCRIPTION = 'Install Icecat demo data http://icecat.biz';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messenger = $this->getMessenger();

        $icecatInstaller = $this->getFacade()->getIcecatDataInstaller($output);
        $icecatInstaller->setMessenger($messenger);

        $icecatInstaller->install();
    }

}
