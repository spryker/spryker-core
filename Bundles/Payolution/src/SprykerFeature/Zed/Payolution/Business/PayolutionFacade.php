<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use Generated\Shared\Payolution\OrderInterface;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PayolutionDependencyContainer getDependencyContainer()
 */
class PayolutionFacade extends AbstractFacade
{

    /**
     * @param OrderInterface $orderTransfer
     */
    public function saveOrderPayment(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createOrderManager()->saveOrderPayment($orderTransfer);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->preAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function reAuthorizePayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->reAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function capturePayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->capturePayment($idPayment);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createTransactionStatusLog()->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createTransactionStatusLog()->isCaptureApproved($orderTransfer);
    }

}
