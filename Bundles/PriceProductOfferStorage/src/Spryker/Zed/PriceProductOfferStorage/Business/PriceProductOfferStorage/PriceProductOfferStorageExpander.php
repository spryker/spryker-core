<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage;

use Generated\Shared\Transfer\PriceProductOfferDataTransfer;
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
            $priceProductOfferDataTransfer = $this->getPriceProductOfferDataTransfer($priceProductOffer, (new PriceProductOfferDataTransfer()));

            $productOfferStorageTransfer->addPrice($priceProductOfferDataTransfer);
        }

        return $productOfferStorageTransfer;
    }

    /**
     * @param array $priceProductOffer
     * @param \Generated\Shared\Transfer\PriceProductOfferDataTransfer $priceProductOfferDataTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferDataTransfer
     */
    protected function getPriceProductOfferDataTransfer(array $priceProductOffer, PriceProductOfferDataTransfer $priceProductOfferDataTransfer): PriceProductOfferDataTransfer
    {
        $priceProductOfferDataTransfer->setPriceType($priceProductOffer[SpyPriceTypeTableMap::COL_NAME]);
        $priceProductOfferDataTransfer->setCurrency($priceProductOffer[SpyCurrencyTableMap::COL_CODE]);
        $priceProductOfferDataTransfer->setNetPrice($priceProductOffer[SpyPriceProductOfferTableMap::COL_NET_PRICE]);
        $priceProductOfferDataTransfer->setGrossPrice($priceProductOffer[SpyPriceProductOfferTableMap::COL_GROSS_PRICE]);

        return $priceProductOfferDataTransfer;
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
