<?php

namespace SprykerEngine\Client\Kernel;

use Generated\Client\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;

class Container extends \Pimple
{

    /**
     * @return AutoCompletion|static
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }

}
