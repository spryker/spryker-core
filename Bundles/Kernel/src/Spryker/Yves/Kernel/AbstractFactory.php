<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Generated\Client\Ide\AutoCompletion;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractFactory implements FactoryInterface
{

    /**
     * @return \Generated\Client\Ide\AutoCompletion|\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
