<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel;

use Generated\Zed\Ide\AutoCompletion;

class Container extends \Pimple
{

    /**
     * @return \Generated\Zed\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }

}
