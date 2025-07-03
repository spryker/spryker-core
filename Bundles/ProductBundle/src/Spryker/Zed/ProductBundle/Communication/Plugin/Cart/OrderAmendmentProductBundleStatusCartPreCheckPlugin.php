<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\TerminationAwareCartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 */
class OrderAmendmentProductBundleStatusCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface, TerminationAwareCartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `OriginalSalesOrderItemTransfer.sku` to be set for each item in `CartChangeTransfer.quote.originalSalesOrderItems`.
     * - Checks if the product bundle items in the `CartChangeTransfer` are active.
     * - Skips validation for items with SKUs from `CartChangeTransfer.quote.originalSalesOrderItems`.
     * - Returns `CartPreCheckResponseTransfer` with `isSuccess` set to `true` if all product bundle items are active.
     * - Otherwise, returns `CartPreCheckResponseTransfer` with `isSuccess` set to `false` and error messages indicating which items are not active.
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
            ->createProductBundleCartActiveCheck()
            ->checkActiveItems($cartChangeTransfer, $originalSalesOrderItemSkus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure()
    {
        return true;
    }
}
