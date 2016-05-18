<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Braintree\Business\BraintreeFacade getFacade()
 * @method \Spryker\Zed\Braintree\Communication\BraintreeCommunicationFactory getFactory()
 */
class AuthorizePlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $customerEntity = $orderEntity->getCustomer();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerEntity->toArray(), true);

        $orderTransfer = $this->getOrderTransfer($orderEntity);
        $orderTransfer->setCustomer($customerTransfer);
        $paymentEntity = $this->getPaymentEntity($orderEntity);

        $this->getFacade()->authorizePayment(
            $orderTransfer,
            $paymentEntity->getIdPaymentBraintree()
        );

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity)
    {
        return $this
            ->getFactory()
            ->getSalesAggregator()
            ->getOrderTotalsByIdSalesOrder($orderEntity->getIdSalesOrder());
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree
     */
    protected function getPaymentEntity(SpySalesOrder $orderEntity)
    {
        $paymentEntity = $orderEntity->getSpyPaymentBraintrees()->getFirst();

        return $paymentEntity;
    }

}
