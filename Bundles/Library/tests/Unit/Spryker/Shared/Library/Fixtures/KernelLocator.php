<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Library\Fixtures;

use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Shared\Kernel\TransferLocator;
use Unit\Spryker\Shared\Kernel\Fixtures\LocatorLocator;

class KernelLocator extends LocatorLocator
{

    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy();

        return $bundleProxy;
    }

}
