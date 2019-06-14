<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;
use Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;

class BehaviorDependencyFinder implements DependencyFinderInterface
{
    public const TYPE_PERSISTENCE = 'persistence';

    /**
     * @var \Zend\Filter\FilterChain|null
     */
    protected $filter;

    /**
     * @var \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface
     */
    protected $moduleFinderFacade;

    /**
     * @param \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade
     */
    public function __construct(DevelopmentToModuleFinderFacadeInterface $moduleFinderFacade)
    {
        $this->moduleFinderFacade = $moduleFinderFacade;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_PERSISTENCE;
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

        if (substr($context->getFileInfo()->getFilename(), -10) !== 'schema.xml' || strpos($context->getFileInfo()->getFilename(), 'spy_') !== 0) {
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
        if (preg_match_all('/<behavior name="(.*?)">/', $context->getFileInfo()->getContents(), $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $moduleName = $this->getModuleNameFromMatch($match);
                if (!$this->isModule($moduleName)) {
                    continue;
                }

                $dependencyContainer = $this->addModuleDependency($dependencyContainer, $moduleName);
            }
        }

        return $dependencyContainer;
    }

    /**
     * @param array $match
     *
     * @return string
     */
    protected function getModuleNameFromMatch(array $match): string
    {
        return ucfirst($this->getFilter()->filter($match[1])) . 'Behavior';
    }

    /**
     * @return \Zend\Filter\FilterChain
     */
    protected function getFilter(): FilterChain
    {
        if ($this->filter === null) {
            $this->filter = new FilterChain();
            $this->filter->attach(new DashToCamelCase());
        }

        return $this->filter;
    }

    /**
     * @param string $moduleName
     *
     * @return bool
     */
    protected function isModule(string $moduleName): bool
    {
        $moduleTransferCollection = $this->moduleFinderFacade->getModules();

        return isset($moduleTransferCollection['Spryker.' . $moduleName]);
    }

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    protected function addModuleDependency(DependencyContainerInterface $dependencyContainer, string $moduleName): DependencyContainerInterface
    {
        $dependencyContainer->addDependency(
            $moduleName,
            $this->getType()
        );

        return $dependencyContainer;
    }
}
