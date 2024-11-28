<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PaymentCartConnector\Business\PaymentCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig getConfig()
 */
class RemoveQuotePaymentCartItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes the payment information from the quote when cart changes are made.
     * - Expects `QuoteTransfer.payment` and `QuoteTransfer.payments` to be set.
     * - Removes `Quote.payment` and `Quote.payments`.
     * - Returns the modified `CartChangeTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->removeQuotePaymentOnCartChange($cartChangeTransfer);
    }
}
