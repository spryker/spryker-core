<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Persistance\Propel\ProductListQueryExpander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class ProductListQueryExpander implements ProductListQueryExpanderInterface
{
    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildProductListMerchantQueryCriteria(): QueryCriteriaTransfer
    {
        return (new QueryCriteriaTransfer())
            ->addJoin(
                $this->createJoin()
            )
            ->setWithColumns([
                SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP => SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP,
            ]);
    }

    /**
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([
                SpyProductListTableMap::COL_FK_MERCHANT_RELATIONSHIP,
            ])
            ->setRight([
                SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP,
            ])
            ->setJoinType(Criteria::LEFT_JOIN);
    }
}
