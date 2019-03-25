<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator\Business;

use Spryker\Client\Kernel\ClientLocator;
use Spryker\Service\Kernel\ServiceLocator;
use Spryker\Zed\Kernel\Business\FacadeLocator;
use Spryker\Zed\Kernel\Persistence\QueryContainerLocator;
use Spryker\Zed\Testify\Locator\AbstractLocator;

class BusinessLocator extends AbstractLocator
{
    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy()
    {
        $locators = [
            new FacadeLocator(),
            new QueryContainerLocator(),
            new ServiceLocator(),
            new ClientLocator(),
        ];

        $bundleProxy = new BundleProxy($this);
        $bundleProxy
            ->setLocators($locators);

        return $bundleProxy;
    }
}
