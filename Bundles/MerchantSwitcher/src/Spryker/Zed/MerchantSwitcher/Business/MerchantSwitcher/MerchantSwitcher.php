<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcher;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Quote\QuoteConfig;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToCartFacadeInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToQuoteFacadeInterface;

class MerchantSwitcher implements MerchantSwitcherInterface
{
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
    public function switch(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        $merchantSwitchRequestTransfer->requireQuote();
        $merchantSwitchRequestTransfer->requireMerchantReference();

        $quoteTransfer = $merchantSwitchRequestTransfer->getQuote();
        $merchantReference = $merchantSwitchRequestTransfer->getMerchantReference();

        $quoteTransfer = $this->switchItemData($quoteTransfer, $merchantReference);

        $quoteTransfer->setMerchantReference(null);
        $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        $quoteTransfer->setMerchantReference($merchantReference);

        if ($this->quoteFacade->getStorageStrategy() === QuoteConfig::STORAGE_STRATEGY_DATABASE) {
            $this->quoteFacade->updateQuote($quoteTransfer)->getQuoteTransfer();
        }

        return (new MerchantSwitchResponseTransfer())
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function switchItemData(QuoteTransfer $quoteTransfer, string $merchantReference): QuoteTransfer
    {
        $skus = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        $merchantProductOfferCriteriaFilterTransfer = new MerchantProductOfferCriteriaFilterTransfer();
        $merchantProductOfferCriteriaFilterTransfer->setMerchantReference($merchantReference);
        $merchantProductOfferCriteriaFilterTransfer->setSkus($skus);

        $merchantProductOfferCollectionTransfer = $this->merchantProductOfferFacade->getProductOfferCollection($merchantProductOfferCriteriaFilterTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($merchantProductOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
                if ($productOfferTransfer->getConcreteSku() === $itemTransfer->getSku()) {
                    $itemTransfer->setMerchantReference($merchantReference);
                    $itemTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
                }
            }
        }

        return $quoteTransfer;
    }
}
