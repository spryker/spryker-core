<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Generated\Shared\Transfer\DependencyProviderCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class PluginUsageFinderConsole extends Console
{
    protected const COMMAND_NAME = 'dev:plugin:usage';
    protected const ARGUMENT_MODULE = 'module';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->addArgument(static::ARGUMENT_MODULE, InputArgument::OPTIONAL, 'Module to run checks for.')
            ->setDescription('Finds all used plugins in the project\'s dependency provider.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dependencyProviderPluginUsageList = $this->getFacade()->getInProjectDependencyProviderUsedPlugins();

        $this->printDependencyProviderPluginUsageList($dependencyProviderPluginUsageList);

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer
     *
     * @return void
     */
    protected function printDependencyProviderPluginUsageList(DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer): void
    {
        foreach ($dependencyProviderCollectionTransfer->getDependencyProvider() as $dependencyProviderTransfer) {
            if (count($dependencyProviderTransfer->getUsedPlugins()) === 0) {
                continue;
            }
            $table = new Table($this->output);
            $table->setHeaders([$dependencyProviderTransfer->getClassName()]);

            foreach ($dependencyProviderTransfer->getUsedPlugins() as $pluginTransfer) {
                $table->addRow([$pluginTransfer->getClassName()]);
            }

            $table->render();
        }
    }
}
