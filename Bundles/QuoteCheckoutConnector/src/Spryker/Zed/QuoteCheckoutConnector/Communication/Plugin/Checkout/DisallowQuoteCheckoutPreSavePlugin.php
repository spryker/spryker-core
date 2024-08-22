<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteCheckoutConnector\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\QuoteCheckoutConnector\Business\QuoteCheckoutConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorConfig getConfig()
 */
class DisallowQuoteCheckoutPreSavePlugin extends AbstractPlugin implements CheckoutPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Disallows quote checkout for the configured amount of seconds.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->disallowCheckoutForQuote($quoteTransfer);
    }
}
