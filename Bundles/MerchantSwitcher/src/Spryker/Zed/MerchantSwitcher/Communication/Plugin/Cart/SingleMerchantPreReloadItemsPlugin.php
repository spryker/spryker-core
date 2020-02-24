<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\Communication\MerchantSwitcherCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 */
class SingleMerchantPreReloadItemsPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds product offer substitution for items in cart depending on the selected merchant.
     * - Changes ItemTransfer.productOfferReference to the value from the substitution merchant reference.
     * - Changes ItemTransfer.merchantReference property to the value from the substitution product offer reference.
     * - Requires MerchantSwitchRequestTransfer.quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $skus = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        $merchantProductOfferCriteriaFilterTransfer = (new MerchantProductOfferCriteriaFilterTransfer())
            ->setMerchantReference($quoteTransfer->getMerchantReference())
            ->setSkus($skus)
            ->setIsActive(true);

        $merchantProductOfferCollectionTransfer = $this->getFactory()
            ->getMerchantProductOfferFacade()
            ->getProductOfferCollection($merchantProductOfferCriteriaFilterTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->switchItemData($itemTransfer, $merchantProductOfferCollectionTransfer, $quoteTransfer->getMerchantReference());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $merchantProductOfferCollectionTransfer
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function switchItemData(
        ItemTransfer $itemTransfer,
        ProductOfferCollectionTransfer $merchantProductOfferCollectionTransfer,
        string $merchantReference
    ): ItemTransfer {
        foreach ($merchantProductOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if ($productOfferTransfer->getConcreteSku() === $itemTransfer->getSku()) {
                return $itemTransfer
                    ->setMerchantReference($merchantReference)
                    ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
            }
        }

        return $itemTransfer;
    }
}
