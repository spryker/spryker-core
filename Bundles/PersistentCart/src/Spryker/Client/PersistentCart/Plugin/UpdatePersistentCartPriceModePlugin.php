<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceExtension\Dependency\Plugin\PriceModePostUpdatePluginInterface;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartClientInterface getClient()
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 */
class UpdatePersistentCartPriceModePlugin extends AbstractPlugin implements PriceModePostUpdatePluginInterface
{
    /**
     *  Specification:
     *   - Plugin executed after price mode is changed.
     *
     * @api
     *
     * @param string $priceMode
     *
     * @return void
     */
    public function execute(string $priceMode): void
    {
        if ($this->getFactory()->getQuoteClient()->getStorageStrategy() !== QuoteConfig::STORAGE_STRATEGY_DATABASE) {
            return;
        }
        $quoteUpdateRequestTransfer = new QuoteUpdateRequestTransfer();
        $quoteUpdateRequestTransfer->setIdQuote($this->getFactory()->getQuoteClient()->getQuote()->getIdQuote());
        $quoteUpdateRequestTransfer->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());
        $quoteUpdateRequestAttributesTransfer = new QuoteUpdateRequestAttributesTransfer();
        $quoteUpdateRequestAttributesTransfer->setPriceMode($priceMode);
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        $this->getFactory()->createZedPersistentCartStub()->updateQuote($quoteUpdateRequestTransfer);
    }
}
