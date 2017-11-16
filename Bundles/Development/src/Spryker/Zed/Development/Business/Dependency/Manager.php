<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;
use Symfony\Component\Finder\Finder;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;

class Manager implements ManagerInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\BundleParserInterface
     */
    protected $moduleParser;

    /**
     * @var string
     */
    protected $moduleDirectories;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\BundleParserInterface $moduleParser
     * @param string[] $moduleDirectories
     */
    public function __construct(BundleParserInterface $moduleParser, $moduleDirectories)
    {
        $this->moduleParser = $moduleParser;
        $this->moduleDirectories = array_filter($moduleDirectories, 'is_dir');
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
            $moduleDependencyCollectionTransfer = $this->moduleParser->parseOutgoingDependencies($foreignModule);
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
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return bool|\Generated\Shared\Transfer\DependencyBundleTransfer|mixed
     */
    protected function findDependencyTo($moduleName, BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        foreach ($moduleDependencyCollectionTransfer->getDependencyBundles() as $dependencyModule) {
            if ($dependencyModule->getBundle() === $moduleName) {
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
            $allModules[] = $filterChain->filter($module->getFilename());
        }
        asort($allModules);

        return $allModules;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function collectCoreModules()
    {
        $modules = (new Finder())->directories()->depth('== 0')->in($this->moduleDirectories);

        return $modules;
    }
}
