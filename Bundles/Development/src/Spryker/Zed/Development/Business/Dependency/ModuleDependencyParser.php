<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContext;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleFileFinder\ModuleFileFinderInterface;

class ModuleDependencyParser implements ModuleDependencyParserInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ModuleFileFinder\ModuleFileFinderInterface
     */
    protected $moduleFileFinder;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    protected $dependencyContainer;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    protected $dependencyFinder;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\ModuleFileFinder\ModuleFileFinderInterface $moduleFileFinder
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface $dependencyFinder
     */
    public function __construct(ModuleFileFinderInterface $moduleFileFinder, DependencyContainerInterface $dependencyContainer, DependencyFinderInterface $dependencyFinder)
    {
        $this->moduleFileFinder = $moduleFileFinder;
        $this->dependencyContainer = $dependencyContainer;
        $this->dependencyFinder = $dependencyFinder;
    }

    /**
     * @param string $module
     * @param string|null $dependencyType
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    public function parseOutgoingDependencies(string $module, ?string $dependencyType = null): DependencyCollectionTransfer
    {
        $dependencyContainer = $this->dependencyContainer->initialize($module);

        $moduleFiles = $this->moduleFileFinder->find($module);

        foreach ($moduleFiles as $moduleFile) {
            $dependencyFinderContext = new DependencyFinderContext($module, $moduleFile, $dependencyType);
            $dependencyContainer = $this->dependencyFinder->findDependencies($dependencyFinderContext, $dependencyContainer);
        }

        return $dependencyContainer->getDependencyCollection();
    }
}
