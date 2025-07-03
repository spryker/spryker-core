<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalBusinessFactory getBusinessFactory()
 */
class OrderAmendmentProductApprovalCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `CartChangeTransfer.quote.originalSalesOrderItems.sku` to be set.
     * - Checks the approval status for products.
     * - Skips check for items that are part of the original order.
     * - Returns `CartPreCheckResponseTransfer` with errors in case cart items have not approved products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $itemSkusToSkipValidation = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($cartChangeTransfer->getQuoteOrFail());

        return $this->getBusinessFactory()
            ->createProductApprovalCartChangeValidator()
            ->validateCartChange($cartChangeTransfer, $itemSkusToSkipValidation);
    }
}
