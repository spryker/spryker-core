<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\RefundPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Refund\Persistence\SpyRefundQuery;

/**
 * @method RefundPersistence getFactory()
 */
class RefundQueryContainer extends AbstractQueryContainer implements RefundQueryContainerInterface
{

    /**
     * @return SpyRefundQuery
     */
    public function queryRefund()
    {
        return (new SpyRefundQuery());
    }

    /**
     * @param int $idOrder
     *
     * @return SpyRefundQuery
     */
    public function queryRefundsByIdSalesOrder($idOrder)
    {
        $query = SpyRefundQuery::create();
        $query->filterByFkSalesOrder($idOrder);

        return $query;
    }

    /**
     * @param int $idMethod
     *
     * @return SpyRefundQuery
     */
    public function queryRefundByIdRefund($idMethod)
    {
        $query = $this->queryRefund();
        $query->filterByIdRefund($idMethod);

        return $query;
    }

}
