<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;

/**
 * @method PayolutionDependencyContainer getDependencyContainer()
 */
class PayolutionFacade extends AbstractFacade
{

    /**
     * @param int $idOrder
     * @param string $clientIp
     *
     * @return PreAuthorizationResponse
     */
    public function preAuthorizePaymentFromOrder($idOrder, $clientIp)
    {
        return $this->getDependencyContainer()->createPaymentManager()->preAuthorizePaymentFromOrder($idOrder, $clientIp);
    }

}
