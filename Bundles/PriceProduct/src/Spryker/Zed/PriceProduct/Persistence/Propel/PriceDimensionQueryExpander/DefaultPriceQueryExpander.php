<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence\Propel\PriceDimensionQueryExpander;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductDefaultTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class DefaultPriceQueryExpander implements DefaultPriceQueryExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildDefaultPriceDimensionQueryCriteria(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?QueryCriteriaTransfer {
        return (new QueryCriteriaTransfer())
            ->addJoin(
                $this->createJoin(),
            )
            ->setWithColumns([
                SpyPriceProductDefaultTableMap::COL_ID_PRICE_PRODUCT_DEFAULT => PriceProductDimensionTransfer::ID_PRICE_PRODUCT_DEFAULT,
            ]);
    }

    /**
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([
                SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE,
            ])
            ->setRight([
                SpyPriceProductDefaultTableMap::COL_FK_PRICE_PRODUCT_STORE,
            ])
            ->setJoinType(Criteria::LEFT_JOIN);
    }
}
