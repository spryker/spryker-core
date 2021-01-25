<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\DependencyModuleTransfer;
use Generated\Shared\Transfer\DependencyModuleViewTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\DashToCamelCase;
use Spryker\Zed\Development\Business\Dependency\Mapper\DependencyModuleMapperInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;

class Manager implements ManagerInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface
     */
    protected $moduleParser;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\Mapper\DependencyModuleMapperInterface
     */
    protected $dependencyModuleMapper;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface $moduleParser
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\Dependency\Mapper\DependencyModuleMapperInterface $dependencyModuleMapper
     */
    public function __construct(
        ModuleDependencyParserInterface $moduleParser,
        DevelopmentConfig $config,
        DependencyModuleMapperInterface $dependencyModuleMapper
    ) {
        $this->moduleParser = $moduleParser;
        $this->config = $config;
        $this->dependencyModuleMapper = $dependencyModuleMapper;
    }

    /**
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\DependencyModuleViewTransfer[]
     */
    public function parseIncomingDependencies(string $moduleName): array
    {
        $allForeignModuleNames = $this->collectAllForeignModules($moduleName);

        $incomingDependencies = [];

        foreach ($allForeignModuleNames as $foreignModuleName) {
            $organizationTransfer = (new OrganizationTransfer())->setName('Spryker');
            $moduleTransfer = (new ModuleTransfer())
                ->setName($foreignModuleName)
                ->setOrganization($organizationTransfer);

            $moduleDependencyCollectionTransfer = $this->moduleParser->parseOutgoingDependencies($moduleTransfer);

            $dependencyModuleTransfer = $this->findDependencyTo($moduleName, $moduleDependencyCollectionTransfer);

            if ($dependencyModuleTransfer === null) {
                continue;
            }

            $dependencyModuleViewTransfer = $this->dependencyModuleMapper->mapDependencyModuleTransferToDependencyModuleViewTransfer(
                $dependencyModuleTransfer,
                new DependencyModuleViewTransfer()
            );
            $dependencyModuleViewTransfer->setName($foreignModuleName);

            $incomingDependencies[] = $dependencyModuleViewTransfer;
        }

        return $incomingDependencies;
    }

    /**
     * @param string $moduleName
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyModuleTransfer|null
     */
    protected function findDependencyTo(
        string $moduleName,
        DependencyCollectionTransfer $moduleDependencyCollectionTransfer
    ): ?DependencyModuleTransfer {
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $dependencyModule) {
            if ($dependencyModule->getModule() !== $moduleName) {
                continue;
            }

            return $dependencyModule;
        }

        return null;
    }

    /**
     * @param string $moduleName
     *
     * @return array
     */
    protected function collectAllForeignModules($moduleName)
    {
        $modules = $this->collectCoreModules();
        $allForeignModules = [];

        foreach ($modules as $module) {
            $foreignModuleName = $module->getFilename();
            if ($foreignModuleName !== $moduleName) {
                $allForeignModules[] = $foreignModuleName;
            }
        }
        asort($allForeignModules);

        return $allForeignModules;
    }

    /**
     * @return array
     */
    public function collectAllModules()
    {
        $modules = $this->collectCoreModules();
        $allModules = [];

        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());

        foreach ($modules as $module) {
            $allModules[$module->getPathname()] = $filterChain->filter($module->getFilename());
        }
        asort($allModules);

        return $allModules;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function collectCoreModules()
    {
        $moduleDirectories = array_filter($this->config->getPathsToInternalNamespace(), 'is_dir');
        $modules = (new Finder())->directories()->depth('== 0')->in($moduleDirectories);

        return $modules;
    }
}
