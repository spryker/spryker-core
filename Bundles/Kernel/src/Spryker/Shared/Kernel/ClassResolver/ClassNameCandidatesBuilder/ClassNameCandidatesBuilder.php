<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder;

use Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderInterface;

class ClassNameCandidatesBuilder implements ClassNameCandidatesBuilderInterface
{
    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderInterface
     */
    protected $moduleNameCandidatesBuilder;

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderConfigInterface
     */
    protected $config;

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderInterface $moduleNameCandidatesBuilder
     * @param \Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderConfigInterface $config
     */
    public function __construct(ModuleNameCandidatesBuilderInterface $moduleNameCandidatesBuilder, ClassNameCandidatesBuilderConfigInterface $config)
    {
        $this->moduleNameCandidatesBuilder = $moduleNameCandidatesBuilder;
        $this->config = $config;
    }

    /**
     * @param string $moduleName
     * @param string $classNamePattern
     *
     * @return string[]
     */
    public function buildClassNames(string $moduleName, string $classNamePattern): array
    {
        $classNames = [];

        $classNames = $this->addProjectClassNames($moduleName, $classNames, $classNamePattern);
        $classNames = $this->addCoreClassNames($moduleName, $classNames, $classNamePattern);

        return $classNames;
    }

    /**
     * @param string $moduleName
     * @param string[] $classNames
     * @param string $classNamePattern
     *
     * @return string[]
     */
    protected function addProjectClassNames(string $moduleName, array $classNames, string $classNamePattern): array
    {
        foreach ($this->moduleNameCandidatesBuilder->buildModuleNameCandidates($moduleName) as $moduleNameCandidate) {
            foreach ($this->config->getProjectOrganizations() as $projectOrganization) {
                $classNames[] = $this->buildClassNameCandidate($projectOrganization, $moduleName, $moduleNameCandidate, $classNamePattern);
            }
        }

        return $classNames;
    }

    /**
     * @param string $moduleName
     * @param string[] $classNames
     * @param string $classNamePattern
     *
     * @return string[]
     */
    protected function addCoreClassNames(string $moduleName, array $classNames, string $classNamePattern): array
    {
        foreach ($this->config->getCoreOrganizations() as $coreOrganization) {
            $classNames[] = $this->buildClassNameCandidate($coreOrganization, $moduleName, $moduleName, $classNamePattern);
        }

        return $classNames;
    }

    /**
     * @param string $organization
     * @param string $moduleName
     * @param string $moduleNameCandidate
     * @param string $classNamePattern
     *
     * @return string
     */
    protected function buildClassNameCandidate(string $organization, string $moduleName, string $moduleNameCandidate, string $classNamePattern): string
    {
        return sprintf($classNamePattern, $organization, $moduleNameCandidate, $moduleName);
    }
}
