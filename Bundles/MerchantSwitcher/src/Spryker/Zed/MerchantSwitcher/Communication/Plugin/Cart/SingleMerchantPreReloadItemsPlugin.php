<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Cart;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 */
class SingleMerchantPreReloadItemsPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds product offer substitution for items in cart depending on the provided merchant reference.
     * - Changes ItemTransfer.productOfferReference to the value from the substitution merchant reference.
     * - Changes ItemTransfer.merchantReference property to the value from the substitution product offer reference.
     * - Requires QuoteTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$this->getConfig()->isMerchantSwitcherEnabled()) {
            return $quoteTransfer;
        }

        if (!$quoteTransfer->getMerchantReference()) {
            return $quoteTransfer;
        }

        $merchantSwitcherRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setMerchantReference($quoteTransfer->getMerchantReference())
            ->setQuote($quoteTransfer);

        return $this->getFacade()
            ->switchMerchantInQuoteItems($merchantSwitcherRequestTransfer)
            ->getQuote();
    }
}
