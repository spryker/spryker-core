<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Command;

use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\PayoneOmsConnector\Communication\PayoneOmsConnectorDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method PayoneOmsConnectorDependencyContainer getDependencyContainer()
 */
class RefundPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array Array
     */

    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $refundTransfer = new PayoneRefundTransfer();
        $amount = $this->calculateAmount($orderItems);
        $refundTransfer->setAmount($amount * -1);

        $narrativeText = $this->getDependencyContainer()->getConfig()->getNarrativeText($orderItems, $orderEntity, $data);
        $refundTransfer->setNarrativeText($narrativeText);
        $refundTransfer->setUseCustomerdata(PayoneApiConstants::USE_CUSTOMER_DATA_YES);

        $paymentPayoneEntity = $orderEntity->getSpyPaymentPayones()->getFirst();

        $payonePaymentTransfer = new PayonePaymentTransfer();
        $payonePaymentTransfer->fromArray($paymentPayoneEntity->toArray(), true);
        $refundTransfer->setPayment($payonePaymentTransfer);

        $this->getDependencyContainer()->createPayoneFacade()->refundPayment($refundTransfer);
        return [];
    }

    /**
     * @param array $orderItems
     * @return int Amount
     */
    protected function calculateAmount($orderItems) {
        $amount = 0;
        /* @var SpySalesOrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $amount += $orderItem->getPriceToPay();
        }
        return $amount;
    }

}
