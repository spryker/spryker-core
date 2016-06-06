<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionFacade getFacade()
 * @method \Spryker\Zed\Payolution\Communication\PayolutionCommunicationFactory getFactory()
 */
class CapturePlugin extends AbstractPlugin implements CommandByOrderInterface
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
        $orderTransfer = $this->getOrderTransfer($orderEntity);
        $paymentEntity = $this->getPaymentEntity($orderEntity);

        $this->getFacade()->capturePayment(
            $orderTransfer,
            $paymentEntity->getIdPaymentPayolution()
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
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    protected function getPaymentEntity(SpySalesOrder $orderEntity)
    {
        $paymentEntity = $orderEntity->getSpyPaymentPayolutions()->getFirst();

        return $paymentEntity;
    }

}
