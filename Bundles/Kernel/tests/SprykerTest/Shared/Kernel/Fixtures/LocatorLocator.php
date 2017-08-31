<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Shared\Kernel\BundleProxy;

class LocatorLocator extends AbstractLocatorLocator
{

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy()
    {
        return new BundleProxy();
    }

}
