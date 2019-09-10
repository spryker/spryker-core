<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface;
use Spryker\Zed\Development\DevelopmentConfig;

class InternalDependencyFinder extends AbstractFileDependencyFinder
{
    public const TYPE_INTERNAL = 'internal';

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
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_INTERNAL;
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

        foreach ($dependencyModules as $filePath => $composerNames) {
            foreach ($composerNames as $composerName) {
                $dependencyContainer->addDependency(
                    $composerName,
                    $this->getType(),
                    $this->isOptional($filePath, $composerName),
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
        return ($this->isPluginFile($filePath) && !$this->isExtensionModule($module) && !$this->isTestFile($filePath));
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

        $composerNames = $this->getNamesFromUseStatements($useStatements, $context->getModule()->getName());

        if (count($composerNames) > 0) {
            $dependencyModules[$context->getFileInfo()->getRealPath()] = array_unique($composerNames);
        }

        return $dependencyModules;
    }

    /**
     * @param array $useStatements
     * @param string $module
     *
     * @return array
     */
    protected function getNamesFromUseStatements(array $useStatements, string $module): array
    {
        $dependentComposerNames = [];
        foreach ($useStatements as $useStatement) {
            $useStatementFragments = explode('\\', $useStatement);
            if ($this->isIgnorableUseStatement($useStatementFragments)) {
                continue;
            }

            $foreignModule = $useStatementFragments[2];
            if ($foreignModule === $module) {
                continue;
            }

            if ($useStatementFragments[0] === 'Orm') {
                $dependentComposerNames[] = $useStatementFragments[2];
                continue;
            }

            $dependentComposerNames[] = $this->buildComposerName($useStatementFragments[0], $useStatementFragments[2]);
        }

        return $dependentComposerNames;
    }

    /**
     * @param array $useStatementFragments
     *
     * @return bool
     */
    protected function isIgnorableUseStatement(array $useStatementFragments): bool
    {
        return (!in_array($useStatementFragments[0], $this->config->getInternalNamespaces()) || !in_array($useStatementFragments[1], $this->config->getApplications()));
    }
}
