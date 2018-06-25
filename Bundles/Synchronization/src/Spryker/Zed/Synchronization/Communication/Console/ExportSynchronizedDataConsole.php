<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface getFacade()
 */
class ExportSynchronizedDataConsole extends Console
{
    const COMMAND_NAME = 'sync:data';
    const DESCRIPTION = 'Exports synchronized data into queues';
    const RESOURCE = 'resource';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument(static::RESOURCE, InputArgument::OPTIONAL, 'Defines which resource(s) should be exported, if there is more than one, use comma to separate them. 
        If not, full export will be executed.');

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
        $resources = [];
        if ($input && $input->getArgument(static::RESOURCE)) {
            $resourceString = $input->getArgument(static::RESOURCE);
            $resources = explode(',', $resourceString);
        }

        $this->getFacade()->executeResolvedPluginsBySources($resources);
    }
}
