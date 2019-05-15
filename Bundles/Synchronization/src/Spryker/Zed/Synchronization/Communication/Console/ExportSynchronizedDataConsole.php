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
 * @method \Spryker\Zed\Synchronization\Communication\SynchronizationCommunicationFactory getFactory()
 */
class ExportSynchronizedDataConsole extends Console
{
    public const COMMAND_NAME = 'sync:data';
    public const DESCRIPTION = 'Exports synchronized data into queues';
    public const RESOURCE = 'resource';
    public const OPTION_IDS = 'ids';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument(static::RESOURCE, InputArgument::OPTIONAL, 'Defines which resource(s) should be exported, if there is more than one, use comma to separate them. 
        If not, full export will be executed.');
        $this->addArgument(static::OPTION_IDS, InputArgument::OPTIONAL, 'Defines ids for entities which should be exported, if there is more than one, use comma to separate them. 
        If not, full export will be executed.');

        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->addUsage($this->getResourcesUsageText());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resources = [];
        $ids = [];

        if ($input->getArgument(static::RESOURCE)) {
            $resourceString = $input->getArgument(static::RESOURCE);
            $resources = explode(',', $resourceString);
        }

        if ($input->getArgument(static::OPTION_IDS)) {
            $resourceString = $input->getArgument(static::OPTION_IDS);
            $ids = explode(',', $resourceString);

            $ids = array_map(function ($id) {
                return (int)$id;
            }, $ids);
        }

        $this->getFacade()->executeResolvedPluginsBySourcesWithIds($resources, $ids);
    }

    /**
     * @return string
     */
    protected function getResourcesUsageText(): string
    {
        $availableResourceNames = $this->getFacade()->getAvailableResourceNames();

        return sprintf(
            "[\n\t%s\n]",
            implode(",\n\t", $availableResourceNames)
        );
    }
}
