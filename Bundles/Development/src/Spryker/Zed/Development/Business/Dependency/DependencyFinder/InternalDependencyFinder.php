<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface;
use Spryker\Zed\Development\DevelopmentConfig;

class InternalDependencyFinder extends AbstractFileDependencyFinder
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface
     */
    protected $useStatementParser;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface $useStatementParser
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(UseStatementParserInterface $useStatementParser, DevelopmentConfig $config)
    {
        $this->useStatementParser = $useStatementParser;
        $this->config = $config;
    }

    /**
     * @param string $module
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function findDependencies(string $module, DependencyContainerInterface $dependencyContainer): DependencyContainerInterface
    {
        $dependencyModules = $this->getDependencyModules($module);

        foreach ($dependencyModules as $filePath => $modules) {
            foreach ($modules as $dependentModule) {
                $dependencyContainer->addDependency(
                    $dependentModule,
                    'spryker',
                    $this->isOptional($filePath, $dependentModule),
                    $this->isTestFile($filePath)
                );
            }
        }

        return $dependencyContainer;
    }

    /**
     * @param string $filePath
     * @param string $module
     *
     * @return bool
     */
    protected function isOptional(string $filePath, string $module): bool
    {
        return ($this->isPluginFile($filePath) && !$this->isExtensionModule($module));
    }

    /**
     * @param string $module
     *
     * @return array
     */
    protected function getDependencyModules(string $module): array
    {
        $dependencyModules = [];
        $useStatements = $this->useStatementParser->getUseStatements($module);

        foreach ($useStatements as $fileName => $fileUseStatements) {
            $modules = $this->getModuleNamesFromUseStatements($fileUseStatements, $module);
            if (count($modules) > 0) {
                $dependencyModules[$fileName] = array_unique($modules);
            }
        }

        return $dependencyModules;
    }

    /**
     * @param array $useStatements
     * @param string $module
     *
     * @return array
     */
    protected function getModuleNamesFromUseStatements(array $useStatements, string $module): array
    {
        $dependentModules = [];
        foreach ($useStatements as $useStatement) {
            $useStatementFragments = explode('\\', $useStatement);
            if (!in_array($useStatementFragments[0], $this->config->getInternalNamespaces())) {
                continue;
            }
            $foreignModule = $useStatementFragments[2];
            if ($foreignModule !== $module) {
                $dependentModules[] = $foreignModule;
            }
        }

        return $dependentModules;
    }
}
