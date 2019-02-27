<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesRepository extends AbstractRepository implements SalesRepositoryInterface
{
    protected const ID_SALES_ORDER = 'id_sales_order';

    /**
     * @param string $customerReference
     * @param string $orderReference
     *
     * @return int|null
     */
    public function findCustomerOrderIdByOrderReference(string $customerReference, string $orderReference): ?int
    {
        $idSalesOrder = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByCustomerReference($customerReference)
            ->filterByOrderReference($orderReference)
            ->select([static::ID_SALES_ORDER])
            ->findOne();

        return $idSalesOrder;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getCustomerOrderListByCustomerReference(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByCustomerReference($orderListTransfer->getCustomerReference());
        $numberOfOrders = $orderListQuery->count();
        if (!$numberOfOrders) {
            return $orderListTransfer;
        }

        $filterTransfer = $orderListTransfer->getFilter();
        if ($orderListTransfer->getFilter()) {
            $orderListQuery
                ->setLimit($filterTransfer->getLimit())
                ->setOffset($filterTransfer->getOffset());
        }

        $orderListTransfer = $this->getFactory()
            ->createOrderListTransferMapper()
            ->mapOrderListTransfer($orderListTransfer, $orderListQuery->find()->getArrayCopy(), $numberOfOrders);

        return $orderListTransfer;
    }
}
