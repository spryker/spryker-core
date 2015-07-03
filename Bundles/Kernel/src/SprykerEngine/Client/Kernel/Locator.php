<?php

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\BundleProxy;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;

class Locator extends AbstractLocatorLocator
{

    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        if (is_null($this->locator)) {
            $locator = [];
        }
        $bundleProxy->setLocator($this->locator);

        return $bundleProxy;
    }
}
