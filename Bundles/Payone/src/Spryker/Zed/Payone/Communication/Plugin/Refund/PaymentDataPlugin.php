<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Refund;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payone\Business\PayoneFacade;
use Spryker\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface;
use Spryker\Zed\Payone\Communication\PayoneCommunicationFactory;

/**
 * @method PayoneFacade getFacade()
 * @method PayoneCommunicationFactory getFactory()
 */
class PaymentDataPlugin extends AbstractPlugin implements PaymentDataPluginInterface
{

    /**
     * @param int $idOrder
     *
     * @return PaymentDataTransfer
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
