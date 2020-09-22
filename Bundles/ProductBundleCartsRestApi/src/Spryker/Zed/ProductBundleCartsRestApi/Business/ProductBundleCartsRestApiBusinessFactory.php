<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleCartsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundleCartsRestApi\Business\Validator\BundleItemValidator;
use Spryker\Zed\ProductBundleCartsRestApi\Business\Validator\BundleItemValidatorInterface;

/**
 * @method \Spryker\Zed\ProductBundleCartsRestApi\ProductBundleCartsRestApiConfig getConfig()
 */
class ProductBundleCartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBundleCartsRestApi\Business\Validator\BundleItemValidatorInterface
     */
    public function createBundleItemValidator(): BundleItemValidatorInterface
    {
        return new BundleItemValidator();
    }
}
