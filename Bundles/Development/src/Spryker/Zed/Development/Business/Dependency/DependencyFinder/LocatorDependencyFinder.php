<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;
use Symfony\Component\Finder\SplFileInfo;

class LocatorDependencyFinder implements DependencyFinderInterface
{
    public const TYPE_LOCATOR = 'locator';

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_LOCATOR;
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

        if ($context->getFileInfo()->getExtension() !== 'php' || strpos($context->getFileInfo()->getFilename(), 'DependencyProvider.php') === false) {
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

        foreach ($dependencyModules as $module) {
            $dependencyContainer->addDependency(
                $module,
                $this->getType()
            );
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
        return $this->getLocatedModulesFromDependencyProvider($context->getFileInfo());
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return array
     */
    protected function getLocatedModulesFromDependencyProvider(SplFileInfo $fileInfo): array
    {
        $dependencyModules = [];

        if (preg_match_all('/->(?<module>\w+?)\(\)->(client|facade|queryContainer|service|resource)\(\)/', $fileInfo->getContents(), $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $dependencyModules[] = ucfirst($match['module']);
            }
        }

        return $dependencyModules;
    }
}
