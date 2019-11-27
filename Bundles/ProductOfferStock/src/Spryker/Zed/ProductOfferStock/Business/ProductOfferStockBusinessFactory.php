<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferStock\Business\ProductOfferStock\ProductOfferStockReader;
use Spryker\Zed\ProductOfferStock\Business\ProductOfferStock\ProductOfferStockReaderInterface;

/**
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 */
class ProductOfferStockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStock\Business\ProductOfferStock\ProductOfferStockReaderInterface
     */
    public function createProductOfferStockReader(): ProductOfferStockReaderInterface
    {
        return new ProductOfferStockReader($this->getRepository());
    }
}
