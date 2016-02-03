<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
