<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSwitcher\MerchantReferenceSwitcher;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToMerchantProductOfferClientInterface;

class MerchantReferenceSwitcher implements MerchantReferenceSwitcherInterface
{
    /**
     * @var \Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToMerchantProductOfferClientInterface
     */
    protected $merchantProductOfferClient;

    /**
     * @param \Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToMerchantProductOfferClientInterface $merchantProductOfferClient
     */
    public function __construct(
        MerchantSwitcherToMerchantProductOfferClientInterface $merchantProductOfferClient
    ) {
        $this->merchantProductOfferClient = $merchantProductOfferClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switch(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        $merchantSwitchRequestTransfer->requireQuote();
        $quoteTransfer = $merchantSwitchRequestTransfer->getQuote();

        $skus = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        $merchantProductOfferCriteriaFilterTransfer = new MerchantProductOfferCriteriaFilterTransfer();
        $merchantProductOfferCriteriaFilterTransfer->setMerchantReference($quoteTransfer->getMerchantReference());
        $merchantProductOfferCriteriaFilterTransfer->setSkus($skus);

        $merchantProductOfferCollectionTransfer = $this->merchantProductOfferClient->getProductOfferCollection($merchantProductOfferCriteriaFilterTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($merchantProductOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
                if ($productOfferTransfer->getConcreteSku() === $itemTransfer->getSku()) {
                    $itemTransfer->setMerchantReference($quoteTransfer->getMerchantReference());
                    $itemTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
                }
            }
        }

        return (new MerchantSwitchResponseTransfer())
            ->setQuote($quoteTransfer);
    }
}
