<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\AllowableDatabaseStrategyPluginInterface;

/**
 * @method \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteRequest\Communication\QuoteRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestAllowableDatabaseStrategyPlugin extends AbstractPlugin implements AllowableDatabaseStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     * - Disallow database strategy when quoteRequestReference provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isAllowed(QuoteTransfer $quoteTransfer): bool
    {
        return !(bool)$quoteTransfer->getQuoteRequestReference();
    }
}
