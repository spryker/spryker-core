<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Table;

interface OrdersTableQueryBuilderInterface
{

    /**
     * @param int|null $idOrderItemProcess
     * @param int|null $idOrderItemState
     * @param string|null $dateFilter
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function buildQuery($idOrderItemProcess = null, $idOrderItemState = null, $dateFilter = null);

}
