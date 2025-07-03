<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 */
class OrderAmendmentProductBundleAvailabilityCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `OriginalSalesOrderItemTransfer.sku` to be set for each item in `CartChangeTransfer.quote.originalSalesOrderItems`.
     * - Checks if the product bundle items in the `CartChangeTransfer` are available and active.
     * - Skips `isActive` validation for items with SKUs from `CartChangeTransfer.quote.originalSalesOrderItems`.
     * - Returns `CartPreCheckResponseTransfer` with `isSuccess` set to `true` if all product bundle items are available.
     * - Otherwise, returns `CartPreCheckResponseTransfer` with `isSuccess` set to `false` and adds error messages indicating which items are not available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $originalSalesOrderItemSkus = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($cartChangeTransfer->getQuoteOrFail());

        return $this->getBusinessFactory()
            ->createProductBundleCartPreCheck()
            ->checkCartAvailability($cartChangeTransfer, $originalSalesOrderItemSkus);
    }
}
