<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin\Command;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use SprykerFeature\Zed\Payone\Communication\PayoneDependencyContainer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 * @method PayoneFacade getFacade()
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

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntity->toArray(), true);

        $amount = $this->getDependencyContainer()
            ->createRefundFacade()
            ->calculateRefundableAmount($orderTransfer);
        $refundTransfer->setAmount($amount * -1);

        $paymentPayoneEntity = $orderEntity->getSpyPaymentPayones()->getFirst();

        $payonePaymentTransfer = new PayonePaymentTransfer();
        $payonePaymentTransfer->fromArray($paymentPayoneEntity->toArray(), true);

        $refundTransfer->setPayment($payonePaymentTransfer);
        $refundTransfer->setUseCustomerdata(PayoneApiConstants::USE_CUSTOMER_DATA_YES);

        $narrativeText = $this->getDependencyContainer()->getConfig()->getNarrativeText($orderItems, $orderEntity, $data);
        $refundTransfer->setNarrativeText($narrativeText);

        $this->getFacade()->refundPayment($refundTransfer);

        return [];
    }

}
