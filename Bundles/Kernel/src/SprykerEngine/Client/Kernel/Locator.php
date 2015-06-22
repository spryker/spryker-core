<?php

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\BundleProxy;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Zed\Kernel\Communication\PluginLocator;

class Locator extends AbstractLocatorLocator
{

    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        if (is_null($this->locator)) {

            $storageLocatorClassName = 'SprykerFeature\Client\KvStorage\StorageLocator';
            if (class_exists($storageLocatorClassName)) {
                $this->locator[] = $storageLocatorClassName;
            }

            $this->locator[] = new PluginLocator();

        }
        $bundleProxy->setLocator($this->locator);

        return $bundleProxy;
    }
}
