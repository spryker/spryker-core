<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;

class DependencyFinderComposite implements DependencyFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface[]
     */
    protected $dependencyFinder;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface[] $dependencyFinder
     */
    public function __construct(array $dependencyFinder)
    {
        $this->dependencyFinder = $dependencyFinder;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'all';
    }

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface $context
     *
     * @return bool
     */
    public function accept(DependencyFinderContextInterface $context): bool
    {
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
        foreach ($this->dependencyFinder as $dependencyFinder) {
            if (!$dependencyFinder->accept($context)) {
                continue;
            }
            $dependencyContainer = $dependencyFinder->findDependencies($context, $dependencyContainer);
        }

        return $dependencyContainer;
    }
}
