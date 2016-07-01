<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class RefundSaver implements RefundSaverInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        $refundEntity = $this->buildRefundEntity($refundTransfer);

        foreach ($refundTransfer->getItems() as $itemTransfer) {
            $salesOrderItemEntity = $this->getSalesOrderItem($itemTransfer);
            $salesOrderItemEntity->setCanceledAmount($itemTransfer->getCanceledAmount());
            $salesOrderItemEntity->save();
        }

        $affectedRows = $refundEntity->save();

        return ($affectedRows > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefund
     */
    protected function buildRefundEntity(RefundTransfer $refundTransfer)
    {
        $refundEntity = new SpyRefund();
        $refundEntity->fromArray($refundTransfer->toArray());

        return $refundEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItem(ItemTransfer $itemTransfer)
    {
        $salesOrderItemEntity = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        return $salesOrderItemEntity;
    }

}
