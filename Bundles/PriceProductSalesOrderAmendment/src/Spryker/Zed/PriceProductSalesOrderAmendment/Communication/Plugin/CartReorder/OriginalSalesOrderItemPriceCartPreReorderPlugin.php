<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\Business\PriceProductSalesOrderAmendmentBusinessFactory getBusinessFactory()
 */
class OriginalSalesOrderItemPriceCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `CartReorderRequestTransfer.isAmendment` flag is not set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.unitPrice` to be set.
     * - Builds a group key for each item in `CartReorderTransfer.orderItems`.
     * - Adds original sales order item unit prices to `CartReorderTransfer.quote.originalSalesOrderItemUnitPrices` with group keys as array keys.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function preReorder(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        if (!$cartReorderRequestTransfer->getIsAmendment()) {
            return $cartReorderTransfer;
        }

        return $this->getBusinessFactory()
            ->createCartReorderItemHydrator()
            ->hydrateOriginalSalesOrderItemPrices($cartReorderTransfer);
    }
}
