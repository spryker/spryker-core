<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\DependencyProviderCollectionTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
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
    protected const COMMAND_NAME = 'dev:plugin-usage:dump';
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
        $dependencyProviderCollectionTransfer = $this->getFacade()->getInProjectDependencyProviderUsedPlugins(
            $this->buildModuleFilterTransfer()
        );

        $this->printDependencyProviderPluginUsageList($dependencyProviderCollectionTransfer);

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
            $table->setHeaders([$dependencyProviderTransfer->getFullyQualifiedClassName()]);

            foreach ($dependencyProviderTransfer->getUsedPlugins() as $pluginTransfer) {
                $table->addRow([$pluginTransfer->getFullyQualifiedClassName()]);
            }

            $table->render();
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer|null
     */
    protected function buildModuleFilterTransfer(): ?ModuleFilterTransfer
    {
        if (!$this->input->getArgument(static::ARGUMENT_MODULE)) {
            return null;
        }

        $moduleFilterTransfer = new ModuleFilterTransfer();
        $moduleArgument = $this->input->getArgument(static::ARGUMENT_MODULE);

        if (strpos($moduleArgument, '.') === false) {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($moduleArgument);
            $moduleFilterTransfer->setModule($moduleTransfer);

            return $moduleFilterTransfer;
        }

        $this->addFilterDetails($moduleArgument, $moduleFilterTransfer);

        return $moduleFilterTransfer;
    }

    /**
     * @param string $moduleArgument
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addFilterDetails(string $moduleArgument, ModuleFilterTransfer $moduleFilterTransfer): ModuleFilterTransfer
    {
        $moduleFragments = explode('.', $moduleArgument);

        $organization = array_shift($moduleFragments);
        $application = array_shift($moduleFragments);
        $module = array_shift($moduleFragments);

        if ($module === null) {
            $module = $application;
            $application = null;
        }

        $moduleFilterTransfer = $this->addModuleTransfer($moduleFilterTransfer, $module);
        $moduleFilterTransfer = $this->addOrganizationTransfer($moduleFilterTransfer, $organization);
        $moduleFilterTransfer = $this->addApplicationTransfer($moduleFilterTransfer, $application);

        return $moduleFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addModuleTransfer(ModuleFilterTransfer $moduleFilterTransfer, string $module): ModuleFilterTransfer
    {
        if ($module !== '*' && $module !== 'all') {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($module);
            $moduleFilterTransfer->setModule($moduleTransfer);
        }

        return $moduleFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param string $organization
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addOrganizationTransfer(ModuleFilterTransfer $moduleFilterTransfer, string $organization): ModuleFilterTransfer
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName($organization);

        $moduleFilterTransfer->setOrganization($organizationTransfer);

        return $moduleFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param string|null $application
     *
     * @return \Generated\Shared\Transfer\ModuleFilterTransfer
     */
    protected function addApplicationTransfer(ModuleFilterTransfer $moduleFilterTransfer, ?string $application = null): ModuleFilterTransfer
    {
        if ($application === null) {
            return $moduleFilterTransfer;
        }
        $applicationTransfer = new ApplicationTransfer();
        $applicationTransfer->setName($application);

        $moduleFilterTransfer->setApplication($applicationTransfer);

        return $moduleFilterTransfer;
    }
}
