<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\TwigFileFinder\TwigFileFinderInterface;
use Symfony\Component\Finder\SplFileInfo;

class TwigDependencyFinder implements DependencyFinderInterface
{
    public const TYPE_TWIG = 'twig';

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\TwigFileFinder\TwigFileFinderInterface
     */
    protected $twigFileFinder;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface[]
     */
    protected $twigDependencyFinder;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\TwigFileFinder\TwigFileFinderInterface $twigFileFinder
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface[] $twigDependencyFinder
     */
    public function __construct(TwigFileFinderInterface $twigFileFinder, array $twigDependencyFinder)
    {
        $this->twigFileFinder = $twigFileFinder;
        $this->twigDependencyFinder = $twigDependencyFinder;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_TWIG;
    }

    /**
     * @param string $module
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     * @param string|null $dependencyType
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function findDependencies(string $module, DependencyContainerInterface $dependencyContainer, ?string $dependencyType = null): DependencyContainerInterface
    {
        if ($dependencyType !== null && $dependencyType !== $this->getType()) {
            return $dependencyContainer;
        }

        if (!$this->twigFileFinder->hasModuleTwigFiles($module)) {
            return $dependencyContainer;
        }

        foreach ($this->twigFileFinder->findTwigFiles($module) as $twigFileInfo) {
            $dependencyContainer = $this->findDependenciesInFile($module, $twigFileInfo, $dependencyContainer);
        }

        return $dependencyContainer;
    }

    /**
     * @param string $module
     * @param \Symfony\Component\Finder\SplFileInfo $twigFileInfo
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    protected function findDependenciesInFile(string $module, SplFileInfo $twigFileInfo, DependencyContainerInterface $dependencyContainer): DependencyContainerInterface
    {
        foreach ($this->twigDependencyFinder as $twigDependencyFinder) {
            $dependencyContainer = $twigDependencyFinder->checkDependencyInFile($module, $twigFileInfo, $dependencyContainer);
        }

        return $dependencyContainer;
    }
}
