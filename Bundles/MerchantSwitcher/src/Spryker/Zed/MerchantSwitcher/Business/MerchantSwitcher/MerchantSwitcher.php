<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcher;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToCartFacadeInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToQuoteFacadeInterface;

class MerchantSwitcher implements MerchantSwitcherInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @var \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface
     */
    protected $merchantProductOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @param \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface $merchantProductOfferFacade
     * @param \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToCartFacadeInterface $cartFacade
     */
    public function __construct(
        MerchantSwitcherToMerchantProductOfferFacadeInterface $merchantProductOfferFacade,
        MerchantSwitcherToQuoteFacadeInterface $quoteFacade,
        MerchantSwitcherToCartFacadeInterface $cartFacade
    ) {
        $this->merchantProductOfferFacade = $merchantProductOfferFacade;
        $this->quoteFacade = $quoteFacade;
        $this->cartFacade = $cartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuote(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        $merchantSwitchRequestTransfer
            ->requireQuote()
            ->requireMerchantReference();

        $quoteTransfer = $merchantSwitchRequestTransfer->getQuote();
        $quoteTransfer->setMerchantReference($merchantSwitchRequestTransfer->getMerchantReference());

        if ($this->quoteFacade->getStorageStrategy() === static::STORAGE_STRATEGY_DATABASE && $quoteTransfer->getIdQuote()) {
            $quoteTransfer = $this->quoteFacade->updateQuote($quoteTransfer)->getQuoteTransfer();
        }

        return (new MerchantSwitchResponseTransfer())->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuoteItems(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        $merchantSwitchRequestTransfer
            ->requireQuote()
            ->requireMerchantReference();

        $quoteTransfer = $merchantSwitchRequestTransfer->getQuote();
        $merchantReference = $merchantSwitchRequestTransfer->getMerchantReference();

        $skus = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        $merchantProductOfferCriteriaTransfer = (new MerchantProductOfferCriteriaTransfer())
            ->setMerchantReference($merchantReference)
            ->setSkus($skus)
            ->setIsActive(true);

        $merchantProductOfferCollectionTransfer = $this->merchantProductOfferFacade
            ->getProductOfferCollection($merchantProductOfferCriteriaTransfer);

        $switchedItemTransfers = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $switchedItemTransfers[] = $this->switchItemData($itemTransfer, $merchantProductOfferCollectionTransfer, $merchantReference);
        }

        $quoteTransfer->setItems(new ArrayObject($switchedItemTransfers));

        return (new MerchantSwitchResponseTransfer())
            ->setQuote($quoteTransfer);
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
