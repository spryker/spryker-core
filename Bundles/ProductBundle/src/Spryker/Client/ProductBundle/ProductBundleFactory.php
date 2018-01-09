<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper;

class ProductBundleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouperInterface
     */
    public function createProductBundleGrouper()
    {
        // TODO: get rid of cross layer instantiation
        return new ProductBundleGrouper();
    }
}
