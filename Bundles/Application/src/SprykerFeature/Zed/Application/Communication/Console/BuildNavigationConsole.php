<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Console;

use SprykerFeature\Zed\Application\Business\ApplicationFacade;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method ApplicationFacade getFacade()
 */
class BuildNavigationConsole extends Console
{

    const COMMAND_NAME = 'application:build-navigation-cache';
    const DESCRIPTION = 'Build the navigation tree';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getMessenger()->info('Build navigation cache');
        $this->getFacade()->writeNavigationCache();
    }

}
