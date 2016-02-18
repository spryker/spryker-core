<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Refund\Persistence\SpyRefundQuery;

/**
 * @method \Spryker\Zed\Refund\Persistence\RefundPersistenceFactory getFactory()
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
