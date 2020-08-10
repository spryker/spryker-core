<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Publisher\Business\PublisherFacadeInterface getFacade()
 * @method \Spryker\Zed\Publisher\Communication\PublisherCommunicationFactory getFactory()
 */
class PublisherTriggerEventsConsole extends Console
{
    public const COMMAND_NAME = 'publish:trigger-events';
    public const DESCRIPTION = 'This command will publish Zed resource(Product, Price, ...) to storage and search.';

    public const RESOURCE_OPTION = 'resource';
    public const RESOURCE_OPTION_SHORTCUT = 'r';

    public const RESOURCE_IDS_OPTION = 'ids';
    public const RESOURCE_IDS_OPTION_SHORTCUT = 'i';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption(
            static::RESOURCE_OPTION,
            static::RESOURCE_OPTION_SHORTCUT,
            InputArgument::OPTIONAL,
            'The resource(s) should be published, if there is more than one, use comma to separate them. 
        If not, full data will be published.'
        );

        $this->addOption(
            static::RESOURCE_IDS_OPTION,
            static::RESOURCE_IDS_OPTION_SHORTCUT,
            InputArgument::OPTIONAL,
            'Defines ids of entities which should be published, if there is more than one, use comma to separate them. 
        If not, all ids will be published.'
        );

        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->addUsage(sprintf('-%s resource_name -%s 1,5', static::RESOURCE_OPTION_SHORTCUT, static::RESOURCE_IDS_OPTION_SHORTCUT))
            ->addUsage($this->getResourcesUsageText());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resources = [];
        $resourcesIds = [];

        if ($input->getOption(static::RESOURCE_OPTION)) {
            $resourceString = $input->getOption(static::RESOURCE_OPTION);
            $resources = explode(',', $resourceString);
        }

        if ($input->getOption(static::RESOURCE_IDS_OPTION)) {
            $idsString = $input->getOption(static::RESOURCE_IDS_OPTION);
            $resourcesIds = explode(',', $idsString);
        }

        $resourcePublisherPlugins = $this->getFactory()->getPublisherTriggerPlugins();

        $this->getFactory()->getEventBehaviorFacade()->executeResolvedPluginsBySources($resources, $resourcesIds, $resourcePublisherPlugins);
    }

    /**
     * @return string
     */
    protected function getResourcesUsageText(): string
    {
        $availableResourceNames = $this->getFactory()->getEventBehaviorFacade()->getAvailableResourceNames(
            $this->getFactory()->getPublisherTriggerPlugins()
        );

        return sprintf(
            "-%s [\n\t%s\n]",
            static::RESOURCE_OPTION_SHORTCUT,
            implode(",\n\t", $availableResourceNames)
        );
    }
}
