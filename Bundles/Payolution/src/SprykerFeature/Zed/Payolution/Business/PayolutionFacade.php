<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;


use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

class PayolutionFacade extends AbstractFacade
{
    /**
     * @param int $idOrder
     *
     * @return AuthorizationResponseContainer
     */
    public function preAuthorizePaymentFromOrder($idOrder)
    {
        return $this->getDependencyContainer()->createPaymentManager()->preAuthorizePaymentFromOrder($idOrder);
    }
}
