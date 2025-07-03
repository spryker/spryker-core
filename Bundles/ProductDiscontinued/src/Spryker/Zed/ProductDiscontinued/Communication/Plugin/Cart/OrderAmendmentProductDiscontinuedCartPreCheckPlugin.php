<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\TerminationAwareCartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinued\Communication\ProductDiscontinuedCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig getConfig()
 */
class OrderAmendmentProductDiscontinuedCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface, TerminationAwareCartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `CartChangeTransfer.quote.originalSalesOrderItems.sku` to be set.
     * - Checks all items related products from cart change request if they are not discontinued.
     * - Returns `CartPreCheckResponseTransfer` with errors in case cart items have discontinued products.
     * - Skips items that are part of the original order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer)
    {
        $itemSkusToSkipValidation = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($cartChangeTransfer->getQuoteOrFail());

        return $this->getBusinessFactory()
            ->createCartChangePreCheck()
            ->checkCartItems($cartChangeTransfer, $itemSkusToSkipValidation);
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
