<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Command;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Spryker\Zed\Payolution\Business\PayolutionFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Payolution\Communication\PayolutionCommunicationFactory;

/**
 * @method PayolutionFacade getFacade()
 * @method PayolutionCommunicationFactory getFactory()
 */
class PreAuthorizePlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $paymentEntity = $this->getPaymentEntity($orderEntity);
        $this->getFacade()->preAuthorizePayment($paymentEntity->getIdPaymentPayolution());

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    protected function getPaymentEntity(SpySalesOrder $orderEntity)
    {
        $paymentEntity = $orderEntity->getSpyPaymentPayolution();

        return $paymentEntity;
    }

}
