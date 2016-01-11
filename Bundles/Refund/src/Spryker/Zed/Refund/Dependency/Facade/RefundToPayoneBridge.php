<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Spryker\Zed\Payone\Business\PayoneFacade;

class RefundToPayoneBridge implements RefundToPayoneInterface
{

    /**
     * @var PayoneFacade
     */
    protected $payoneFacade;

    /**
     * @param PayoneFacade $payoneFacade
     */
    public function __construct($payoneFacade)
    {
        $this->payoneFacade = $payoneFacade;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer)
    {
        return $this->payoneFacade->isPaymentDataRequired($orderTransfer);
    }

    /**
     * @param PaymentDataTransfer $paymentData
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentData, $idOrder)
    {
        $this->payoneFacade->updatePaymentDetail($paymentData, $idOrder);
    }

}
