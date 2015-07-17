<?php

namespace SprykerEngine\Client\Kernel;

use Generated\Client\Ide\AutoCompletion;

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
