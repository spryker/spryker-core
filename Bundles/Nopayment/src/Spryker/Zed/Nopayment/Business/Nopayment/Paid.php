<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Business\Nopayment;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use Spryker\Zed\Nopayment\Persistence\NopaymentQueryContainer;
use Orm\Zed\Nopayment\Persistence\SpyNopaymentPaid;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Exception\PropelException;

class Paid
{

    /**
     * @var NopaymentQueryContainer
     */
    protected $queryContainer;

    public function __construct(QueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function setOrderItemAsPaid(SpySalesOrderItem $orderItem)
    {
        $paidItem = new SpyNopaymentPaid();
        $paidItem->setOrderItem($orderItem);
        $paidItem->save();
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     *
     * @return SpySalesOrderItem[]
     */
    public function setAsPaid(array $orderItems)
    {
        foreach ($orderItems as $orderItem) {
            $this->setOrderItemAsPaid($orderItem);
        }

        return $orderItems;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function isPaid(SpySalesOrderItem $orderItem)
    {
        return ($this->queryContainer->queryOrderItem($orderItem)->count() > 0);
    }

}
