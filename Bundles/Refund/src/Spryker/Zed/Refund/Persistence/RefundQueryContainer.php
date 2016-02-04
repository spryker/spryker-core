<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Refund\Persistence\SpyRefundQuery;

/**
 * @method RefundPersistenceFactory getFactory()
 */
class RefundQueryContainer extends AbstractQueryContainer implements RefundQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    public function queryRefund()
    {
        return $this->getFactory()->createRefundQuery();
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    public function queryRefundsByIdSalesOrder($idOrder)
    {
        $query = $this->getFactory()->createRefundQuery();
        $query->filterByFkSalesOrder($idOrder);

        return $query;
    }

    /**
     * @param int $idMethod
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    public function queryRefundByIdRefund($idMethod)
    {
        $query = $this->queryRefund();
        $query->filterByIdRefund($idMethod);

        return $query;
    }

}
