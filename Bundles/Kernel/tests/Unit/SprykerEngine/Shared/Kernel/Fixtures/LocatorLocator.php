<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Shared\Kernel\BundleProxy;

class LocatorLocator extends AbstractLocatorLocator
{

    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        return new BundleProxy($this);
    }

}
