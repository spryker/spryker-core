<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin\Refund;

use Generated\Shared\Refund\PaymentDataInterface;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDataTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use SprykerFeature\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface;

/**
 * @method PayoneFacade getFacade()
 */
class PaymentDataPlugin extends AbstractPlugin implements PaymentDataPluginInterface
{

    /**
     * @param int $idOrder
     *
     * @return PaymentDataInterface
     */
    public function getPaymentData($idOrder)
    {
        return $this->getFacade()->getPaymentData($idOrder);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isRefundPossible($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isPaymentDataRequired($orderTransfer);
    }

    /**
     * @param PaymentDataTransfer $paymentData
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentData, $idOrder)
    {
        $this->getFacade()->updatePaymentDetail($paymentData, $idOrder);
    }

}
