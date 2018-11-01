<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 */
class SetDefaultCurrencyBeforeQuoteCreatePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    /**
     * Specification:
     * - Set default currency to quote if it does not have
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getCurrency()) {
            $quoteTransfer->setCurrency($this->getFacade()->getCurrent());
        }

        return $quoteTransfer;
    }
}
