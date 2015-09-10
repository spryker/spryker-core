<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use Generated\Shared\Payolution\OrderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;

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
     * @return PreAuthorizationResponse
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->preAuthorizePayment($idPayment);
    }

}
