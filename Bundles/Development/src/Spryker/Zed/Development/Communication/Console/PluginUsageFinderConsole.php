<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\DependencyProviderCollectionTransfer;
use Generated\Shared\Transfer\DependencyProviderTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Generated\Shared\Transfer\PluginTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class PluginUsageFinderConsole extends Console
{
    protected const COMMAND_NAME = 'dev:plugin:usage';
    protected const ARGUMENT_MODULE = 'module';

    /**
     * @var \Generated\Shared\Transfer\DependencyProviderCollectionTransfer|null
     */
    protected $dependencyProviderCollection;

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
        $dependencyProviderCollectionTransfer = new DependencyProviderCollectionTransfer();

        $dependencyProviderPluginUsageList = $this->getDependencyProviderPluginUsageList($dependencyProviderCollectionTransfer);

        $this->printDependencyProviderPluginUsageList($dependencyProviderPluginUsageList);

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    protected function getDependencyProviderPluginUsageList(DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer): DependencyProviderCollectionTransfer
    {
        $finder = $this->getFinder();
        foreach ($finder as $splFileObject) {
            $dependencyProviderCollectionTransfer = $this->addPluginUsages($dependencyProviderCollectionTransfer, $splFileObject);
        }

        return $dependencyProviderCollectionTransfer;
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

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getFinder(): Finder
    {
        $finder = new Finder();
        $finder->files()->in(APPLICATION_SOURCE_DIR)->name('/DependencyProvider.php/');

        return $finder;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    protected function addPluginUsages(DependencyProviderCollectionTransfer $dependencyProviderCollectionTransfer, SplFileInfo $splFileInfo): DependencyProviderCollectionTransfer
    {
        preg_match_all('/use (.*?);/', $splFileInfo->getContents(), $matches, PREG_SET_ORDER);
        if (count($matches) === 0) {
            return $dependencyProviderCollectionTransfer;
        }

        $dependencyProviderTransfer = $this->buildDependencyProviderTransfer($splFileInfo);
        $dependencyProviderTransfer = $this->addUsedPlugins($dependencyProviderTransfer, $matches);

        $dependencyProviderCollectionTransfer->addDependencyProvider($dependencyProviderTransfer);

        return $dependencyProviderCollectionTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\DependencyProviderTransfer
     */
    protected function buildDependencyProviderTransfer(SplFileInfo $splFileInfo): DependencyProviderTransfer
    {
        $dependencyProviderClassName = str_replace(['.php', DIRECTORY_SEPARATOR], ['', '\\'], $splFileInfo->getRelativePathname());

        $moduleTransfer = $this->buildModuleTransferFromClassName($dependencyProviderClassName);

        $dependencyProviderTransfer = new DependencyProviderTransfer();
        $dependencyProviderTransfer
            ->setClassName($dependencyProviderClassName)
            ->setModule($moduleTransfer);

        return $dependencyProviderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyProviderTransfer $dependencyProviderTransfer
     * @param array $useStatements
     *
     * @return \Generated\Shared\Transfer\DependencyProviderTransfer
     */
    protected function addUsedPlugins(DependencyProviderTransfer $dependencyProviderTransfer, array $useStatements): DependencyProviderTransfer
    {
        foreach ($useStatements as $match) {
            if (preg_match('/Plugin/', $match[1])) {
                $pluginTransfer = $this->buildPluginTransfer($match[1]);
                $dependencyProviderTransfer->addUsedPlugin($pluginTransfer);
            }
        }

        return $dependencyProviderTransfer;
    }

    /**
     * @param string $pluginClassName
     *
     * @return \Generated\Shared\Transfer\PluginTransfer
     */
    protected function buildPluginTransfer(string $pluginClassName): PluginTransfer
    {
        $moduleTransfer = $this->buildModuleTransferFromClassName($pluginClassName);

        $pluginTransfer = new PluginTransfer();
        $pluginTransfer
            ->setClassName($pluginClassName)
            ->setModule($moduleTransfer);

        return $pluginTransfer;
    }

    /**
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransferFromClassName(string $className): ModuleTransfer
    {
        $classNameFragments = explode('\\', $className);

        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName($classNameFragments[0]);

        $applicationTransfer = new ApplicationTransfer();
        $applicationTransfer
            ->setName($classNameFragments[1]);

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($classNameFragments[2])
            ->setOrganization($organizationTransfer)
            ->setApplication($applicationTransfer)
            ->setIsStandalone(false)
            ->setIsInProject($this->isProjectOrganization($classNameFragments[0]));

        return $moduleTransfer;
    }

    /**
     * @param string $organization
     *
     * @return bool
     */
    protected function isProjectOrganization(string $organization): bool
    {
        $projectNamespaces = Config::get(KernelConstants::PROJECT_NAMESPACES);

        return in_array($organization, $projectNamespaces, true);
    }
}
