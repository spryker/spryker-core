<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Client\Kernel\ClientLocator;

class Locator extends AbstractLocatorLocator
{

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy();
        $bundleProxy
            ->addLocator(new ClientLocator());

        return $bundleProxy;
    }

}
