<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface getFacade()
 * @method \Spryker\Zed\ZedNavigation\Communication\ZedNavigationCommunicationFactory getFactory()
 */
class BuildNavigationConsole extends Console
{
    public const COMMAND_NAME = 'navigation:build-cache';
    public const DESCRIPTION = 'Build the navigation tree and persist it';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->addAlias();
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getMessenger()->info('Build navigation cache');
        $this->getFacade()->writeNavigationCache();
    }

    /**
     * @deprecated Remove this in next major. Only for BC reasons. Please use new command name `navigation:build-cache` instead.
     *
     * @return void
     */
    protected function addAlias()
    {
        $this->setAliases(['application:build-navigation-cache']);
    }
}
