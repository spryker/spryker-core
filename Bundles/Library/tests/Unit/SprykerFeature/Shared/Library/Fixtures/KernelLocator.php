<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\Library\Fixtures;

use SprykerEngine\Shared\Kernel\BundleProxy;
use SprykerEngine\Shared\Kernel\TransferLocator;
use Unit\SprykerEngine\Shared\Kernel\Fixtures\LocatorLocator;

class KernelLocator extends LocatorLocator
{

    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        $bundleProxy->addLocator(new TransferLocator());

        return $bundleProxy;
    }

}
