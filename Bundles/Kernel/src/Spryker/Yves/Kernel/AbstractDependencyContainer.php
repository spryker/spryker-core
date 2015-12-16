<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Generated\Yves\Ide\AutoCompletion;
use Spryker\Yves\Kernel\DependencyContainer\DependencyContainerInterface;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractDependencyContainer implements DependencyContainerInterface
{

    /**
     * @deprecated Will be removed soon. Use DependencyProvider instead
     *
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
