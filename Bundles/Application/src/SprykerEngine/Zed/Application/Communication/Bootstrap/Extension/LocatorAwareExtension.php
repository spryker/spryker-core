<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;

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
