<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacadeInterface getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 */
class RefundPaymentPlugin extends BaseCommandPlugin implements CommandByOrderInterface
{
    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderTransfer = $this->getOrderTransfer($orderEntity);
        $orderTransferItems = $this->getOrderItemsTransfer($orderItems);
        $partialOrderTransfer = $this->getPartialOrderTransferByOrderItems($orderItems);
        $this->getFacade()->refundPayment($orderTransfer, $partialOrderTransfer, $orderTransferItems);

        return [];
    }
}
