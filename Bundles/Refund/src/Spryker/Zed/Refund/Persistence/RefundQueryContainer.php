<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Persistence;

use Orm\Zed\Refund\Persistence\SpyRefundQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Refund\Persistence\RefundPersistenceFactory getFactory()
 */
class RefundQueryContainer extends AbstractQueryContainer implements RefundQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefundQuery
     */
    public function queryRefund()
    {
        return $this->getFactory()->createRefundQuery();
    }

    /**
     * @api
     *
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
     * @api
     *
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
