<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

interface ProductPackagingUnitToSalesQueryContainerInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder(int $idSalesOrder): SpySalesOrderItemQuery;
}
