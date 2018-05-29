<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence\Propel\PriceDimensionQueryExpander;

use Generated\Shared\Transfer\PriceDimensionCriteriaTransfer;
use Generated\Shared\Transfer\PriceDimensionJoinTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductDefaultTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class DefaultPriceQueryExpander implements DefaultPriceQueryExpanderInterface
{
    public const COL_ID_PRICE_PRODUCT_STORE = 'spy_price_product_store.id_price_product_store';

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceDimensionCriteriaTransfer|null
     */
    public function buildDefaultPriceDimensionCriteria(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?PriceDimensionCriteriaTransfer {
        return (new PriceDimensionCriteriaTransfer())
            ->addPriceDimensionJoin(
                $this->createJoin()
            )
            ->setWithColumns([
                SpyPriceProductDefaultTableMap::COL_ID_PRICE_PRODUCT_DEFAULT => PriceProductDimensionTransfer::ID_PRICE_PRODUCT_DEFAULT,
            ]);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceDimensionJoinTransfer
     */
    protected function createJoin(): PriceDimensionJoinTransfer
    {
        return (new PriceDimensionJoinTransfer())
            ->setLeft([
                SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE,
            ])
            ->setRight([
                SpyPriceProductDefaultTableMap::COL_FK_PRICE_PRODUCT_STORE,
            ])
            ->setJoinType(Criteria::LEFT_JOIN);
    }
}
