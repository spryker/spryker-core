<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Client\Kernel\ClientLocator;
use SprykerEngine\Shared\Kernel\BundleProxy;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use SprykerEngine\Shared\Kernel\TransferLocator;
use SprykerEngine\Zed\Kernel\Business\FacadeLocator;
use SprykerEngine\Zed\Kernel\Communication\ConsoleLocator;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainerLocator;

class Locator extends AbstractLocatorLocator
{

    /**
     * @return BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy($this);
        if ($this->locator === null) {
            $this->locator = [
                new FacadeLocator(),
                new TransferLocator(),
                new QueryContainerLocator(),
                new ConsoleLocator(),
                new ClientLocator(),
            ];
        }
        $bundleProxy->setLocator($this->locator);

        return $bundleProxy;
    }

}
