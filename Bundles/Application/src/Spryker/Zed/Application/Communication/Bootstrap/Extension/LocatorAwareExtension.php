<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Spryker\Zed\Kernel\Locator;

class LocatorAwareExtension
{

    /**
     * @return \Generated\Zed\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }

}
