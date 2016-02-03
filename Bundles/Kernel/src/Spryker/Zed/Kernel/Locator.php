<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel;

use Spryker\Client\Kernel\ClientLocator;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Zed\Kernel\Business\FacadeLocator;
use Spryker\Zed\Kernel\Persistence\QueryContainerLocator;

class Locator extends AbstractLocatorLocator
{

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy();
        if ($this->locator === null) {
            $this->locator = [
                new FacadeLocator(),
                new QueryContainerLocator(),
                new ClientLocator(),
            ];
        }
        $bundleProxy->setLocator($this->locator);

        return $bundleProxy;
    }

}
