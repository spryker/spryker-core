<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferSales\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferSales\Business\OrderItem\OrderItemExpander;
use Spryker\Zed\ProductOfferSales\Business\OrderItem\OrderItemExpanderInterface;

/**
 * @method \Spryker\Zed\ProductOfferSales\ProductOfferSalesConfig getConfig()
 */
class ProductOfferSalesBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferSales\Business\OrderItem\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }
}
