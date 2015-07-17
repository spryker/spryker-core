<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use Generated\Zed\Ide\AutoCompletion;

class Container extends \Pimple
{

    /**
     * @return AutoCompletion
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }

}
