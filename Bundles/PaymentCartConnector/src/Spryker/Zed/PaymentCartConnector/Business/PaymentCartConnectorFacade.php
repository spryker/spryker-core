<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PaymentCartConnector\Business\PaymentCartConnectorBusinessFactory getFactory()
 */
class PaymentCartConnectorFacade extends AbstractFacade implements PaymentCartConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeQuotePayment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createQuotePaymentRemover()->removeQuotePayment($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function removeQuotePaymentOnCartChange(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()->createQuotePaymentRemover()->removeQuotePaymentOnCartChange($cartChangeTransfer);
    }
}
