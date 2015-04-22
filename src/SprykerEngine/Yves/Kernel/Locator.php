<?php

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Shared\Kernel\BundleProxy;
use SprykerEngine\Shared\Kernel\TransferLocator;
use SprykerEngine\Sdk\Kernel\SdkLocator;

/**
 * Class Locator
 * @package SprykerEngine\Yves\Kernel
 */
class Locator extends AbstractLocatorLocator
{
    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        $bundleProxy
            ->addLocator(new PluginLocator())
            ->addLocator(new TransferLocator())
            ->addLocator(new SdkLocator())
            ->addLocator(new ClientLocator());

        return $bundleProxy;
    }
}
