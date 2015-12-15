<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Generated\Zed\Ide\AutoCompletion;
use Spryker\Zed\Kernel\Locator;

class LocatorAwareExtension
{

    /**
     * @return AutoCompletion
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }

}
