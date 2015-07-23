<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Shared\Kernel\BundleProxy;
use SprykerEngine\Shared\Kernel\TransferLocator;
use SprykerEngine\Client\Kernel\Service\ClientLocator;
use SprykerEngine\Yves\Kernel\Communication\PluginLocator;

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
            ->addLocator(new ClientLocator())
        ;

        return $bundleProxy;
    }

}
