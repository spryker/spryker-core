<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferPersistenceFactory getFactory()
 */
class MerchantProductOfferRepository extends AbstractRepository implements MerchantProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     *
     * @return int[]
     */
    public function getProductOfferIds(MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer): array
    {
        $productOfferQuery = $this->applyFilters(
            $merchantProductOfferCriteriaTransfer,
            $this->getFactory()->getProductOfferPropelQuery()
        );

        $productOfferQuery->select([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER]);

        return $productOfferQuery->find()->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer,
        SpyProductOfferQuery $productOfferQuery
    ): SpyProductOfferQuery {
        if ($merchantProductOfferCriteriaTransfer->getSkus()) {
            $productOfferQuery->filterByConcreteSku_In($merchantProductOfferCriteriaTransfer->getSkus());
        }

        if ($merchantProductOfferCriteriaTransfer->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($merchantProductOfferCriteriaTransfer->getIsActive());
        }

        if ($merchantProductOfferCriteriaTransfer->getMerchantReference()) {
            $productOfferQuery
                ->useSpyMerchantQuery()
                    ->filterByMerchantReference($merchantProductOfferCriteriaTransfer->getMerchantReference())
                ->endUse();
        }

        return $productOfferQuery;
    }
}
