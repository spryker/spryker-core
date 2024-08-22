<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\QuoteCheckoutConnector\Business\QuoteCheckoutConnectorBusinessFactory getFactory()
 */
class QuoteCheckoutConnectorFacade extends AbstractFacade implements QuoteCheckoutConnectorFacadeInterface
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
    public function disallowCheckoutForQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createQuoteCheckoutCondition()->disallowCheckoutForQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isCheckoutAllowedForQuote(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFactory()->createQuoteCheckoutCondition()->isCheckoutAllowedForQuote($quoteTransfer, $checkoutResponseTransfer);
    }
}
