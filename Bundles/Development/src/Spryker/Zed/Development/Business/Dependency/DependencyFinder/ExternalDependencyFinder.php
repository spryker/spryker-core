<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Exception;
use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Zend\Filter\Word\SeparatorToCamelCase;

class ExternalDependencyFinder extends AbstractFileDependencyFinder
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
                    'external',
                    $this->isPluginFile($filePath),
                    $this->isTestFile($filePath)
                );
            }
        }

        return $dependencyContainer;
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
            if ($this->isExternalDependency($useStatement)) {
                $dependentModules[] = $this->getDependentModule($useStatement);
            }
        }

        return $dependentModules;
    }

    /**
     * @param string $useStatement
     *
     * @return bool
     */
    protected function isExternalDependency(string $useStatement): bool
    {
        foreach ($this->config->getExternalToInternalNamespaceMap() as $namespace => $package) {
            if (strpos($useStatement, $namespace) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $useStatement
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getDependentModule(string $useStatement): string
    {
        foreach ($this->config->getExternalToInternalNamespaceMap() as $namespace => $package) {
            if (strpos($useStatement, $namespace) === 0) {
                return $this->getDependentModuleNameFromPackage($package);
            }
        }

        throw new Exception('Could not map external to internal dependency!');
    }

    /**
     * @param string $package
     *
     * @return string
     */
    protected function getDependentModuleNameFromPackage(string $package): string
    {
        $dependentModule = substr($package, 8);
        $filter = new SeparatorToCamelCase('-');

        return ucfirst($filter->filter($dependentModule));
    }
}
