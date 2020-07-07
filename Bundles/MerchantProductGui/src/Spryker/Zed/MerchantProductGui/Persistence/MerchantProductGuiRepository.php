<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Persistence;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

class MerchantProductGuiRepository extends AbstractRepository implements MerchantProductGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteriaTransferWithMerchantProductRelation(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): QueryCriteriaTransfer {
        $queryCriteriaTransfer
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setJoinType(Criteria::INNER_JOIN)
                    ->setRelation('SpyMerchantProductAbstract')
                    ->setCondition(SpyMerchantProductAbstractTableMap::COL_FK_MERCHANT . sprintf(' = %d', $merchantProductCriteriaTransfer->getIdMerchant()))
            );

        return $queryCriteriaTransfer;
    }
}
