<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface;

class ModuleDependencyParser implements ModuleDependencyParserInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    protected $dependencyContainer;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    protected $dependencyFinder;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface $dependencyFinder
     */
    public function __construct(DependencyContainerInterface $dependencyContainer, DependencyFinderInterface $dependencyFinder)
    {
        $this->dependencyContainer = $dependencyContainer;
        $this->dependencyFinder = $dependencyFinder;
    }

    /**
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    public function parseOutgoingDependencies(string $module): DependencyCollectionTransfer
    {
        $this->dependencyContainer->initialize($module);
        $dependencyContainer = $this->dependencyFinder->findDependencies(
            $module,
            $this->dependencyContainer->initialize($module)
        );

        return $dependencyContainer->getDependencyCollection();
    }
}
