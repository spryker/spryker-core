<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayolutionOmsConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;
use SprykerFeature\Zed\PayolutionOmsConnector\PayolutionOmsConnectorDependencyProvider;

class PayolutionOmsConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @throws \ErrorException
     *
     * @return PayolutionFacade
     */
    public function createPayolutionFacade()
    {
        return $this->getProvidedDependency(PayolutionOmsConnectorDependencyProvider::FACADE_PAYOLUTION);
    }

}
