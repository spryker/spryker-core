<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStoragePersistenceFactory getFactory()
 */
class MerchantProductOfferStorageRepository extends AbstractRepository implements MerchantProductOfferStorageRepositoryInterface
{
    /**
     * @var int
     */
    protected const PRODUCT_OFFER_BATCH_SIZE = 1000;

    /**
     * @param array<int> $merchantIds
     *
     * @return array<int, array<string>>
     */
    public function getProductConcreteSkusByMerchantIds(array $merchantIds): iterable
    {
        $productOfferPropelQuery = $this->getFactory()->getProductOfferPropelQuery();
        $productOfferPropelQuery
            ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
            ->addAnd($productOfferPropelQuery->getNewCriterion(SpyMerchantTableMap::COL_ID_MERCHANT, $merchantIds, Criteria::IN))
            ->orderByIdProductOffer();

        $lastIdProductOfferInBatch = 0;
        $limit = static::PRODUCT_OFFER_BATCH_SIZE;
        do {
            $data = $productOfferPropelQuery
                ->filterByIdProductOffer($lastIdProductOfferInBatch, Criteria::GREATER_THAN)
                ->select([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, SpyProductOfferTableMap::COL_CONCRETE_SKU])
                ->setLimit($limit)
                ->distinct()
                ->find()
                ->getData();

            if ($data) {
                $lastIdProductOfferInBatch = max(
                    array_column($data, SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER),
                );

                yield array_column($data, SpyProductOfferTableMap::COL_CONCRETE_SKU);
            }
        } while ($data);
    }

    /**
     * @param array<int> $merchantIds
     * @param int $minIdProductOffer
     * @param int $total
     *
     * @return iterable<array<string>>
     */
    public function iterateProductOfferReferencesByMerchantIds(array $merchantIds, int $minIdProductOffer = 0, int $total = 1000): iterable
    {
        $productOfferPropelQuery = $this->getFactory()->getProductOfferPropelQuery();
        $productOfferPropelQuery
            ->select([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE])
            ->distinct()
            ->addJoin(SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, SpyMerchantTableMap::COL_MERCHANT_REFERENCE, Criteria::INNER_JOIN)
            ->addAnd($productOfferPropelQuery->getNewCriterion(SpyMerchantTableMap::COL_ID_MERCHANT, $merchantIds, Criteria::IN))
            ->orderByIdProductOffer();

        do {
            $data = $productOfferPropelQuery
                ->filterByIdProductOffer($minIdProductOffer, Criteria::GREATER_THAN)
                ->setLimit($total)
                ->find()
                ->getData();

            if ($data) {
                $minIdProductOffer = max(
                    array_column($data, SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER),
                );

                yield array_column($data, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE);
            }
        } while ($data);
    }
}
