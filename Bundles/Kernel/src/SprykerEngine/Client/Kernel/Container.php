<?php

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;

class Container extends \Pimple
{

    /**
     * @return AbstractLocatorLocator|static
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }

}
