<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PaymentCartConnector\Business\PaymentCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig getConfig()
 */
class RemovePaymentCartPostSavePlugin extends AbstractPlugin implements CartOperationPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes the payment information from the quote if not excluded by configuration {@link \Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig::getExcludedPaymentMethods()}.
     * - Expects `QuoteTransfer.payment` and `QuoteTransfer.payments` to be set.
     * - Removes `Quote.payment` and `Quote.payments`.
     * - Returns the modified quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->removeQuotePayment($quoteTransfer);
    }
}
