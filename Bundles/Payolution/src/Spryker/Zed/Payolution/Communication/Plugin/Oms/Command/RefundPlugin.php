<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Spryker\Zed\Payolution\Business\PayolutionFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Payolution\Communication\PayolutionCommunicationFactory;

/**
 * @method PayolutionFacade getFacade()
 * @method PayolutionCommunicationFactory getFactory()
 */
class RefundPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderTransfer = $this->getOrderTransfer($orderEntity);
        $paymentEntity = $this->getPaymentEntity($orderEntity);

        $this->getFacade()->refundPayment(
            $orderTransfer,
            $paymentEntity->getIdPaymentPayolution()
        );

        return [];
    }

    /**
     * @param SpySalesOrder $orderEntity
     *
     * @return OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity)
    {
        return $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderTotalsByIdSalesOrder($orderEntity->getIdSalesOrder());
    }

    /**
     * @param SpySalesOrder $orderEntity
     *
     * @return SpyPaymentPayolution
     */
    protected function getPaymentEntity(SpySalesOrder $orderEntity)
    {
        $paymentEntity = $orderEntity->getSpyPaymentPayolution();

        return $paymentEntity;
    }

}
