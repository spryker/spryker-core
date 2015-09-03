<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\RefundPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefundQuery;

/**
 * @method ShipmentPersistence getFactory()
 */
class RefundQueryContainer extends AbstractQueryContainer implements RefundQueryContainerInterface
{

    /**
     * @param int $idMethod
     *
     * @return SpyRefundQuery
     */
    public function queryRefundByIdRefund($idMethod)
    {
        $query = $this->queryMethods();
        $query->filterByIdRefund($idMethod);

        return $query;
    }

}
