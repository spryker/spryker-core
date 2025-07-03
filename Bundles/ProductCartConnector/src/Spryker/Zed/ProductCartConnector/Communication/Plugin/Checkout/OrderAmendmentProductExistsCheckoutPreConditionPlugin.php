<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCartConnector\Communication\ProductCartConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig getConfig()
 */
class OrderAmendmentProductExistsCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.originalSalesOrderItems.sku` to be set.
     * - Requires either `QuoteTransfer.item.sku` or `QuoteTransfer.item.abstractSku` to be set.
     * - Does nothing if quote items are empty.
     * - Validates if concrete products with sku `QuoteTransfer.item.sku` exist and active.
     * - If `QuoteTransfer.item.sku` is missing, validates if abstract products with sku `QuoteTransfer.item.abstractSku` exist.
     * - Adds corresponding errors to `CheckoutResponseTransfer` in case of failed validation.
     * - Skips items that are part of the original order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $itemSkusToSkipValidation = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($quoteTransfer);

        return $this->getBusinessFactory()
            ->createProductValidator()
            ->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer, $itemSkusToSkipValidation);
    }
}
