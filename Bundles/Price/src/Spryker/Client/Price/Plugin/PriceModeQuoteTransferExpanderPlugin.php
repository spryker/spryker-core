<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\Price\PriceClientInterface getClient()
 * @method \Spryker\Client\Price\PriceFactory getFactory()
 */
class PriceModeQuoteTransferExpanderPlugin extends AbstractPlugin implements QuoteTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPriceMode() !== null) {
            return $quoteTransfer;
        }

        $quoteTransfer->setPriceMode($this->getFactory()->getModuleConfig()->getDefaultPriceMode());

        return $quoteTransfer;
    }
}
