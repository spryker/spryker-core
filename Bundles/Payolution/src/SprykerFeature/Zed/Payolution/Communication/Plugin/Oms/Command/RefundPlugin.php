<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Communication\Plugin\Oms\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method PayolutionFacade getFacade()
 */
class RefundPlugin  extends AbstractPlugin implements CommandByOrderInterface
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

        $this->getFacade()->refundPayment($paymentEntity->getIdPaymentPayolution());

        return [];
    }

}
