<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteUnlockPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\QuoteRequest\QuoteRequestConfig getConfig()
 * @method \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteRequest\Communication\QuoteRequestCommunicationFactory getFactory()
 */
class QuoteUnlockPreCheckPlugin extends AbstractPlugin implements QuoteUnlockPreCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * Returns false if quote in 'request for quote' process.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function can(QuoteTransfer $quoteTransfer): bool
    {
        return !$quoteTransfer->getQuoteRequestVersionReference();
    }
}
