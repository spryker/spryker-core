<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\Store\StoreClientInterface getClient()
 */
class StoreQuoteTransferExpanderPlugin extends AbstractPlugin implements QuoteTransferExpanderPluginInterface
{
    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected static $currentStoreTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getStore() !== null) {
            return $quoteTransfer;
        }

        $quoteTransfer->setStore($this->getCurrentStore());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore(): StoreTransfer
    {
        if (!static::$currentStoreTransfer) {
            static::$currentStoreTransfer = $this->getClient()->getCurrentStore();
        }

        return static::$currentStoreTransfer;
    }
}
