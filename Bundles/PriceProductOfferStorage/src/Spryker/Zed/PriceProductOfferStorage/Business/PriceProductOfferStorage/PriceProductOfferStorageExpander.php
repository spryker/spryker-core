<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage;

use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;

class PriceProductOfferStorageExpander implements PriceProductOfferStorageExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expandWithProductOfferPrices(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        $productOfferStorageTransfer->requireIdProductOffer();

        $idProductOffer = $productOfferStorageTransfer->getIdProductOffer();

        $priceProductOffers = $this->getPriceProductOffersByIdProductOffer($idProductOffer);

        foreach ($priceProductOffers as $priceProductOffer) {
            $priceProductOfferTransfer = $this->getPriceProductOfferTransfer($priceProductOffer, (new PriceProductOfferTransfer()));

            $productOfferStorageTransfer->addPrice($priceProductOfferTransfer);
        }

        return $productOfferStorageTransfer;
    }

    /**
     * @param array $priceProductOffer
     * @param \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTransfer
     */
    protected function getPriceProductOfferTransfer(array $priceProductOffer, PriceProductOfferTransfer $priceProductOfferTransfer)
    {
        $priceProductOfferTransfer->setPriceType($priceProductOffer[SpyPriceTypeTableMap::COL_NAME]);
        $priceProductOfferTransfer->setCurrency($priceProductOffer[SpyCurrencyTableMap::COL_CODE]);
        $priceProductOfferTransfer->setNetPrice($priceProductOffer[SpyPriceProductOfferTableMap::COL_NET_PRICE]);
        $priceProductOfferTransfer->setGrossPrice($priceProductOffer[SpyPriceProductOfferTableMap::COL_GROSS_PRICE]);

        return $priceProductOfferTransfer;
    }

    /**
     * @param int $idProductOffer
     *
     * @return array
     */
    protected function getPriceProductOffersByIdProductOffer(int $idProductOffer): array
    {
        $priceProductOffers = SpyPriceProductOfferQuery::create()
            ->filterByFkProductOffer($idProductOffer)
            ->joinWithSpyProductOffer()
            ->joinWithSpyStore()
            ->joinWithSpyPriceType()
            ->joinWithSpyCurrency()
            ->select([
                SpyCurrencyTableMap::COL_CODE,
                SpyPriceTypeTableMap::COL_NAME,
                SpyPriceProductOfferTableMap::COL_GROSS_PRICE,
                SpyPriceProductOfferTableMap::COL_NET_PRICE,
            ])
            ->find()->toArray();

        return $priceProductOffers;
    }
}
