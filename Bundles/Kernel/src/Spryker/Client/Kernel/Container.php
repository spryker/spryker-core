<?php

namespace Spryker\Client\Kernel;

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
