<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence\Propel\PriceDimensionQueryExpander;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class PriceProductOfferQueryExpander implements PriceProductOfferQueryExpanderInterface
{
    /**
     * @uses \Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE
     */
    public const COL_ID_PRICE_PRODUCT_STORE = 'spy_price_product_store.id_price_product_store';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER
     */
    public const COL_ID_PRODUCT_OFFER = 'spy_product_offer.id_product_offer';

    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE
     */
    public const COL_PRODUCT_OFFER_REFERENCE = 'spy_product_offer.product_offer_reference';

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildPriceProductOfferDimensionQueryCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?QueryCriteriaTransfer
    {
        return $this->createQueryCriteriaTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildUnconditionalPriceProductOfferDimensionQueryCriteria(): QueryCriteriaTransfer
    {
        return $this->createQueryCriteriaTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    protected function createQueryCriteriaTransfer(): QueryCriteriaTransfer
    {
        return (new QueryCriteriaTransfer())
            ->setWithColumns([
                static::COL_PRODUCT_OFFER_REFERENCE => PriceProductDimensionTransfer::PRODUCT_OFFER_REFERENCE,
            ])
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setLeft([static::COL_ID_PRICE_PRODUCT_STORE])
                    ->setRight([SpyPriceProductOfferTableMap::COL_FK_PRICE_PRODUCT_STORE])
                    ->setJoinType(Criteria::LEFT_JOIN)
            )
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setLeft([SpyPriceProductOfferTableMap::COL_FK_PRODUCT_OFFER])
                    ->setRight([static::COL_ID_PRODUCT_OFFER])
                    ->setJoinType(Criteria::LEFT_JOIN)
            );
    }
}
