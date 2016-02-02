<?php

namespace Spryker\Client\Kernel;

use Generated\Client\Ide\AutoCompletion;

class Container extends \Pimple
{

    /**
     * @return \Generated\Client\Ide\AutoCompletion|static
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }

}
