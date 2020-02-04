<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence\Propel\PriceDimensionQueryBuilder;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class PriceProductOfferQueryBuilder implements PriceProductOfferQueryBuilderInterface
{
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
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => PriceProductDimensionTransfer::PRODUCT_OFFER_REFERENCE,
            ])
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setLeft([SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE])
                    ->setRight([SpyPriceProductOfferTableMap::COL_FK_PRICE_PRODUCT_STORE])
                    ->setJoinType(Criteria::LEFT_JOIN)
            )
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setLeft([SpyPriceProductOfferTableMap::COL_FK_PRODUCT_OFFER])
                    ->setRight([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER])
                    ->setJoinType(Criteria::LEFT_JOIN)
            );
    }
}
