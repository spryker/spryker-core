<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Exception;
use Laminas\Filter\Word\SeparatorToCamelCase;
use Spryker\Zed\Development\Business\Dependency\ComposerParser\ExternalDependencyParserInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface;
use Spryker\Zed\Development\DevelopmentConfig;

class ExternalDependencyFinder extends AbstractFileDependencyFinder
{
    public const TYPE_EXTERNAL = 'external';

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface
     */
    protected $useStatementParser;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ComposerParser\ExternalDependencyParserInterface
     */
    protected $externalDependencyParser;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $foundPackagesByUseStatement = [];

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface $useStatementParser
     * @param \Spryker\Zed\Development\Business\Dependency\ComposerParser\ExternalDependencyParserInterface $externalDependencyParser
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(
        UseStatementParserInterface $useStatementParser,
        ExternalDependencyParserInterface $externalDependencyParser,
        DevelopmentConfig $config
    ) {
        $this->useStatementParser = $useStatementParser;
        $this->externalDependencyParser = $externalDependencyParser;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_EXTERNAL;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface $context
     *
     * @return bool
     */
    public function accept(DependencyFinderContextInterface $context): bool
    {
        if ($context->getDependencyType() !== null && $context->getDependencyType() !== $this->getType()) {
            return false;
        }

        if ($context->getFileInfo()->getExtension() !== 'php') {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface $context
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function findDependencies(DependencyFinderContextInterface $context, DependencyContainerInterface $dependencyContainer): DependencyContainerInterface
    {
        $dependencyModules = $this->getDependencyModules($context);

        foreach ($dependencyModules as $filePath => $modules) {
            foreach ($modules as $dependentModule) {
                $dependencyContainer->addDependency(
                    $dependentModule,
                    $this->getType(),
                    $this->isPluginFile($filePath),
                    $this->isTestFile($filePath)
                );
            }
        }

        return $dependencyContainer;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface $context
     *
     * @return array
     */
    protected function getDependencyModules(DependencyFinderContextInterface $context): array
    {
        $dependencyModules = [];
        $useStatements = $this->useStatementParser->getUseStatements($context->getFileInfo());

        $modules = $this->getModuleNamesFromUseStatements($useStatements);

        if (count($modules) > 0) {
            $dependencyModules[$context->getFileInfo()->getRealPath()] = array_unique($modules);
        }

        return $dependencyModules;
    }

    /**
     * @param array $useStatements
     *
     * @return array
     */
    protected function getModuleNamesFromUseStatements(array $useStatements): array
    {
        $dependentModules = [];
        foreach ($useStatements as $useStatement) {
            if ($this->isMappedExternalDependency($useStatement)) {
                $dependentModules[] = $this->getDependentModule($useStatement);

                continue;
            }

            if ($this->isInternalNamespace($useStatement)) {
                continue;
            }

            if ($this->isUnMappedExternalDependency($useStatement)) {
                $dependentModules[] = $this->externalDependencyParser->findPackageNameByNamespace($useStatement);
            }
        }

        return $dependentModules;
    }

    /**
     * @param string $useStatement
     *
     * @return bool
     */
    protected function isInternalNamespace(string $useStatement): bool
    {
        if (strpos($useStatement, '\\') === false) {
            return true;
        }

        foreach ($this->config->getInternalNamespaces() as $namespace) {
            if (strpos($useStatement, $namespace) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $useStatement
     *
     * @return bool
     */
    protected function isMappedExternalDependency(string $useStatement): bool
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
     * @return bool
     */
    protected function isUnMappedExternalDependency(string $useStatement): bool
    {
        $packageName = $this->externalDependencyParser->findPackageNameByNamespace($useStatement);

        if ($packageName !== null) {
            return true;
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
                return $package;
            }
        }

        throw new Exception(sprintf(
            'Could not map "%s" to a internal dependency! Please update "%s" and add a new mapping.',
            $useStatement,
            '\Spryker\Zed\Development\DevelopmentConfig::getExternalToInternalNamespaceMap()'
        ));
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
