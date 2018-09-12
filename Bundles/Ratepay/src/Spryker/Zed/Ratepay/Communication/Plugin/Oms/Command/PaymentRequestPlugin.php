<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacadeInterface getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 */
class PaymentRequestPlugin extends BaseCommandPlugin implements CommandByOrderInterface
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
        $ratepayPaymentInitTransfer = new RatepayPaymentInitTransfer();
        $quotePaymentInitMapper = $this->getFactory()->createPaymentInitMapperByOrder(
            $ratepayPaymentInitTransfer,
            $orderEntity
        );
        $quotePaymentInitMapper->map();

        $this->getFacade()->initPayment($ratepayPaymentInitTransfer);

        $partialOrderTransfer = $this->getPartialOrderTransferByOrderItems($orderItems);

        $ratepayPaymentRequestTransfer = new RatepayPaymentRequestTransfer();
        $quotePaymentRequestMapper = $this->getFactory()->createPaymentRequestMapperByOrder(
            $ratepayPaymentRequestTransfer,
            $ratepayPaymentInitTransfer,
            $this->getOrderTransfer($orderEntity),
            $partialOrderTransfer,
            $orderEntity
        );
        $quotePaymentRequestMapper->map();

        $ratepayResponseTransfer = $this->getFacade()->requestPayment($ratepayPaymentRequestTransfer);
        $this->getFacade()->updatePaymentMethodByPaymentResponse($ratepayResponseTransfer, $ratepayPaymentRequestTransfer->getOrderId());

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getPartialOrderTransferByOrderItems($orderItems)
    {
        $partialOrderTransfer = $this->getFactory()->createOrderTransfer();
        $items = $this->getFactory()->createOrderTransferItems($orderItems);
        $partialOrderTransfer->setItems($items);

        return $this
            ->getFactory()
            ->getSalesAggregator()
            ->getOrderTotalByOrderTransfer($partialOrderTransfer);
    }
}
