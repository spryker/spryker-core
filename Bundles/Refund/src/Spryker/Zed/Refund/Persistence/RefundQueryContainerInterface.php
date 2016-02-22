<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Persistence;

use Orm\Zed\Refund\Persistence\SpyRefundQuery;

interface RefundQueryContainerInterface
{

    /**
     * @param int $idOrder
     *
     * @return SpyRefundQuery
     */

    public function queryRefundsByIdSalesOrder($idOrder);

    /**
     * @param int $idMethod
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    public function queryRefundByIdRefund($idMethod);

}
