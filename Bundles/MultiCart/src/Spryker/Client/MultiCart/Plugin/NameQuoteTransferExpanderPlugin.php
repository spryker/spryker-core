<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient()
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class NameQuoteTransferExpanderPlugin extends AbstractPlugin implements QuoteTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getName() !== null) {
            return $quoteTransfer;
        }
        if ($quoteTransfer->getCustomer()) {
            $quoteTransfer->setName($this->getFactory()->getBundleConfig()->getCustomerQuoteDefaultName());
        }
        $quoteTransfer->setName($this->getFactory()->getBundleConfig()->getGuestQuoteDefaultName());

        return $quoteTransfer;
    }
}
