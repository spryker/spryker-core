<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionGui\Persistence;

use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantProductOption\Persistence\Map\SpyMerchantProductOptionGroupTableMap;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class MerchantProductOptionGuiRepository implements MerchantProductOptionGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteriaTransferWithMerchantProductOptionRelation(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): QueryCriteriaTransfer {
        $queryCriteriaTransfer
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setJoinType(Criteria::INNER_JOIN)
                    ->setRelation('SpyMerchantProductOptionGroup'),
            )
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setJoinType(Criteria::INNER_JOIN)
                    ->setLeft([SpyMerchantProductOptionGroupTableMap::COL_MERCHANT_REFERENCE])
                    ->setRight([SpyMerchantTableMap::COL_MERCHANT_REFERENCE])
                    ->addQueryWhereCondition(
                        (new QueryWhereConditionTransfer())
                            ->setColumn(SpyMerchantTableMap::COL_ID_MERCHANT)
                            ->setValue((string)$merchantProductOptionGroupCriteriaTransfer->getIdMerchant()),
                    ),
            );

        return $queryCriteriaTransfer;
    }
}
