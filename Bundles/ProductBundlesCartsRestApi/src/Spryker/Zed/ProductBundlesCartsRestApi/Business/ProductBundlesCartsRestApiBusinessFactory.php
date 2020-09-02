<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundlesCartsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundlesCartsRestApi\Business\Checker\BundleItemChecker;
use Spryker\Zed\ProductBundlesCartsRestApi\Business\Checker\BundleItemCheckerInterface;

/**
 * @method \Spryker\Zed\ProductBundlesCartsRestApi\ProductBundlesCartsRestApiConfig getConfig()
 */
class ProductBundlesCartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBundlesCartsRestApi\Business\Checker\BundleItemCheckerInterface
     */
    public function createBundleItemChecker(): BundleItemCheckerInterface
    {
        return new BundleItemChecker();
    }
}
