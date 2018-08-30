<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface;
use Symfony\Component\Finder\SplFileInfo;

class LocatorDependencyFinder implements DependencyFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface $finder
     */
    public function __construct(FinderInterface $finder)
    {
        $this->finder = $finder;
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

        foreach ($dependencyModules as $module) {
            $dependencyContainer->addDependency(
                $module,
                'spryker (locator)'
            );
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
        $files = $this->finder->find($module);

        foreach ($files as $file) {
            if (strpos($file->getFilename(), 'DependencyProvider.php') === false) {
                continue;
            }
            $dependencyModules = $this->getLocatedModulesFromDependencyProvider($dependencyModules, $file);
        }

        return $dependencyModules;
    }

    /**
     * @param array $dependencyModules
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return string[]
     */
    protected function getLocatedModulesFromDependencyProvider(array $dependencyModules, SplFileInfo $file): array
    {
        if (preg_match_all('/->(?<module>\w+?)\(\)->(client|facade|queryContainer|service|resource)\(\)/', $file->getContents(), $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $dependencyModules[] = ucfirst($match['module']);
            }
        }

        return $dependencyModules;
    }
}
