<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayolutionCheckoutConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;
use SprykerFeature\Zed\PayolutionCheckoutConnector\PayolutionCheckoutConnectorDependencyProvider;

class PayolutionCheckoutConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @throws \ErrorException
     *
     * @return PayolutionFacade
     */
    public function createPayolutionFacade()
    {
        return $this->getProvidedDependency(PayolutionCheckoutConnectorDependencyProvider::FACADE_PAYOLUTION);
    }

}
