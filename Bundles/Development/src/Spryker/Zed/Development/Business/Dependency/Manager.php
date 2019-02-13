<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;

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
     * @param \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface $moduleParser
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(ModuleDependencyParserInterface $moduleParser, DevelopmentConfig $config)
    {
        $this->moduleParser = $moduleParser;
        $this->config = $config;
    }

    /**
     * @param string $moduleName
     *
     * @return array
     */
    public function parseIncomingDependencies($moduleName)
    {
        $allForeignModules = $this->collectAllForeignModules($moduleName);

        $incomingDependencies = [];
        foreach ($allForeignModules as $foreignModule) {
            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName('Spryker');
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer
                ->setName($moduleName)
                ->setOrganization($organizationTransfer);

            $moduleDependencyCollectionTransfer = $this->moduleParser->parseOutgoingDependencies($moduleTransfer);
            $dependencyModule = $this->findDependencyTo($moduleName, $moduleDependencyCollectionTransfer);

            if ($dependencyModule) {
                if (array_key_exists($foreignModule, $incomingDependencies) === false) {
                    $incomingDependencies[$foreignModule] = 0;
                }
                $incomingDependencies[$foreignModule] += count($dependencyModule->getDependencies());
            }
        }

        return $incomingDependencies;
    }

    /**
     * @param string $moduleName
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return bool|\Generated\Shared\Transfer\DependencyBundleTransfer|mixed
     */
    protected function findDependencyTo($moduleName, DependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        foreach ($moduleDependencyCollectionTransfer->getDependencyModules() as $dependencyModule) {
            if ($dependencyModule->getModule() === $moduleName) {
                foreach ($dependencyModule->getDependencies() as $dependencyTransfer) {
                    if (!$dependencyTransfer->getIsInTest() && !$dependencyTransfer->getIsOptional()) {
                        return $dependencyModule;
                    }
                }
            }
        }

        return false;
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
