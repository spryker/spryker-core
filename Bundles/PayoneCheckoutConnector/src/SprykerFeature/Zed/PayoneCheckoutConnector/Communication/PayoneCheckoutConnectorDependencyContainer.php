<?php
/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\PayoneCheckoutConnector\Communication;

use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use SprykerFeature\Zed\PayoneCheckoutConnector\PayoneCheckoutConnectorDependencyProvider;

class PayoneCheckoutConnectorDependencyContainer
{

    /**
     * @return PayoneFacade
     */
    public function createPayoneFacade()
    {
        return $this->getProvidedDependency(PayoneCheckoutConnectorDependencyProvider::FACADE_PAYONE);
    }

}
