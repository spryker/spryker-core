<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Persistence;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

class ProductApprovalGuiRepository extends AbstractRepository implements ProductApprovalGuiRepositoryInterface
{
    /**
     * @Module Product
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        return $queryCriteriaTransfer->addWithColumn(
            [SpyProductAbstractTableMap::COL_APPROVAL_STATUS => ProductAbstractTransfer::APPROVAL_STATUS],
        );
    }
}
