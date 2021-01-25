<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContext;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface;
use Spryker\Zed\Development\Business\Module\ModuleFileFinder\ModuleFileFinderInterface;

class ModuleDependencyParser implements ModuleDependencyParserInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Module\ModuleFileFinder\ModuleFileFinderInterface
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
     * @param \Spryker\Zed\Development\Business\Module\ModuleFileFinder\ModuleFileFinderInterface $moduleFileFinder
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface $dependencyFinder
     */
    public function __construct(
        ModuleFileFinderInterface $moduleFileFinder,
        DependencyContainerInterface $dependencyContainer,
        DependencyFinderInterface $dependencyFinder
    ) {
        $this->moduleFileFinder = $moduleFileFinder;
        $this->dependencyContainer = $dependencyContainer;
        $this->dependencyFinder = $dependencyFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param string|null $dependencyType
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    public function parseOutgoingDependencies(ModuleTransfer $moduleTransfer, ?string $dependencyType = null): DependencyCollectionTransfer
    {
        if ($moduleTransfer->getNameDashed() == null) {
            $moduleTransfer->setNameDashed($this->dasherize($moduleTransfer->getName()));
        }

        $dependencyContainer = $this->dependencyContainer->initialize($moduleTransfer);

        if (!$this->moduleFileFinder->hasFiles($moduleTransfer)) {
            return $dependencyContainer->getDependencyCollection();
        }

        $moduleFiles = $this->moduleFileFinder->find($moduleTransfer);

        foreach ($moduleFiles as $moduleFile) {
            $dependencyFinderContext = new DependencyFinderContext($moduleTransfer, $moduleFile, $dependencyType);
            $dependencyContainer = $this->dependencyFinder->findDependencies($dependencyFinderContext, $dependencyContainer);
        }

        return $dependencyContainer->getDependencyCollection();
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function dasherize(string $value): string
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($value);
    }
}
