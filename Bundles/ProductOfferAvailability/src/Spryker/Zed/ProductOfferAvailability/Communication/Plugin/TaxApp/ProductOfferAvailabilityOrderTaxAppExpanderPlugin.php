<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Communication\Plugin\TaxApp;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferAvailability\Business\ProductOfferAvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailability\ProductOfferAvailabilityConfig getConfig()
 */
class ProductOfferAvailabilityOrderTaxAppExpanderPlugin extends AbstractPlugin implements OrderTaxAppExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `OrderTransfer.Item.merchantStockAddresses` with colletion of `MerchantStockAddress` split by quantity to ship from each stock.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expand(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFacade()->expandOrderItemsWithMerchantStockAddressSplitByStockAvailability($orderTransfer);
    }
}
