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
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
