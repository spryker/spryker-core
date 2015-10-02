<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\PayolutionOmsConnector\Communication\PayolutionOmsConnectorDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method PayolutionOmsConnectorDependencyContainer getDependencyContainer()
 */
class CapturePlugin extends AbstractPlugin implements CommandByOrderInterface
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
        /** @var SpyPaymentPayolution $paymentEntity */
        $paymentEntity = $orderEntity->getSpyPaymentPayolutions()->getFirst();

        $this->getDependencyContainer()
            ->createPayolutionFacade()
            ->capturePayment($paymentEntity->getIdPaymentPayolution());

        return [];
    }

}
