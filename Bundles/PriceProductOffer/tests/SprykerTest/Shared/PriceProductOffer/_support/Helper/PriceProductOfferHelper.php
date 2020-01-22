<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProductOffer\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PriceProductOfferBuilder;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use SprykerTest\Shared\Currency\Helper\CurrencyDataHelper;
use SprykerTest\Shared\PriceProduct\Helper\PriceProductDataHelper;
use SprykerTest\Shared\Store\Helper\StoreDataHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\ProductOffer\Helper\ProductOfferHelper;

class PriceProductOfferHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTransfer
     */
    public function havePriceProductOffer(array $seedData = []): PriceProductOfferTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer */
        $priceProductOfferTransfer = (new PriceProductOfferBuilder($seedData))->build();
        $priceProductOfferTransfer->setIdPriceProductOffer(null);

        $priceProductOfferTransfer = $this->setPriceProductOfferDependencies($priceProductOfferTransfer);

        $priceProductOfferEntity = new SpyPriceProductOffer();
        $priceProductOfferEntity->fromArray($priceProductOfferTransfer->toArray());
        $priceProductOfferEntity->save();

        $priceProductOfferTransfer->fromArray($priceProductOfferEntity->toArray());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($priceProductOfferEntity): void {
                $priceProductOfferEntity->delete();
        });

        return $priceProductOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTransfer
     */
    protected function setPriceProductOfferDependencies(PriceProductOfferTransfer $priceProductOfferTransfer): PriceProductOfferTransfer
    {
        if (!$priceProductOfferTransfer->getFkStore()) {
            $priceProductOfferTransfer->setFkStore($this->getStoreDataHelper()->haveStore()->getIdStore());
        }

        if (!$priceProductOfferTransfer->getFkProductOffer()) {
            $productOfferTransfer = $this->getProductOfferHelper()->haveProductOffer();
            $priceProductOfferTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
            $priceProductOfferTransfer->setFkProductOffer($productOfferTransfer->getIdProductOffer());
        }

        if (!$priceProductOfferTransfer->getFkCurrency()) {
            $priceProductOfferTransfer->setFkCurrency($this->getCurrencyDataHelper()->haveCurrency());
        }

        if (!$priceProductOfferTransfer->getFkPriceType()) {
            $priceProductOfferTransfer->setFkPriceType($this->getPriceProductDataHelper()->havePriceType()->getIdPriceType());
        }

        return $priceProductOfferTransfer;
    }

    /**
     * @return \SprykerTest\Zed\ProductOffer\Helper\ProductOfferHelper
     */
    protected function getProductOfferHelper(): ProductOfferHelper
    {
        /** @var \SprykerTest\Zed\ProductOffer\Helper\ProductOfferHelper $productOfferHelper */
        $productOfferHelper = $this->getModule('\\' . ProductOfferHelper::class);

        return $productOfferHelper;
    }

    /**
     * @return \SprykerTest\Shared\Currency\Helper\CurrencyDataHelper
     */
    protected function getCurrencyDataHelper(): CurrencyDataHelper
    {
        /** @var \SprykerTest\Shared\Currency\Helper\CurrencyDataHelper $currencyDataHelper */
        $currencyDataHelper = $this->getModule('\\' . CurrencyDataHelper::class);

        return $currencyDataHelper;
    }

    /**
     * @return \SprykerTest\Shared\Store\Helper\StoreDataHelper
     */
    protected function getStoreDataHelper(): StoreDataHelper
    {
        /** @var \SprykerTest\Shared\Store\Helper\StoreDataHelper $storeDataHelper */
        $storeDataHelper = $this->getModule('\\' . StoreDataHelper::class);

        return $storeDataHelper;
    }

    /**
     * @return \SprykerTest\Shared\PriceProduct\Helper\PriceProductDataHelper
     */
    protected function getPriceProductDataHelper(): PriceProductDataHelper
    {
        /** @var \SprykerTest\Shared\PriceProduct\Helper\PriceProductDataHelper $priceProductDataHelper */
        $priceProductDataHelper = $this->getModule('\\' . PriceProductDataHelper::class);

        return $priceProductDataHelper;
    }
}
