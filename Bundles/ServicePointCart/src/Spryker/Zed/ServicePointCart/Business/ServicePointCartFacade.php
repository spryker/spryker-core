<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCart\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ServicePointCart\Business\ServicePointCartBusinessFactory getFactory()
 */
class ServicePointCartFacade extends AbstractFacade implements ServicePointCartFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteItemReplacer()
            ->replaceQuoteItems($quoteTransfer);
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
    public function validateCheckoutQuoteItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFactory()->createQuoteItemServicePointValidator()->validate($quoteTransfer, $checkoutResponseTransfer);
    }
}
