<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Symfony\Component\Finder\SplFileInfo;

interface TwigDependencyFinderInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $module
     * @param \Symfony\Component\Finder\SplFileInfo $twigFileInfo
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function checkDependencyInFile(string $module, SplFileInfo $twigFileInfo, DependencyContainerInterface $dependencyContainer): DependencyContainerInterface;
}
