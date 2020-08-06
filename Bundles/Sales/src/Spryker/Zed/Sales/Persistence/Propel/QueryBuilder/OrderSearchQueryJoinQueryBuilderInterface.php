<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\QueryBuilder;

use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

interface OrderSearchQueryJoinQueryBuilderInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\QueryJoinCollectionTransfer $queryJoinCollectionTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function addSalesOrderQueryFilters(
        SpySalesOrderQuery $salesOrderQuery,
        QueryJoinCollectionTransfer $queryJoinCollectionTransfer
    ): SpySalesOrderQuery;
}
