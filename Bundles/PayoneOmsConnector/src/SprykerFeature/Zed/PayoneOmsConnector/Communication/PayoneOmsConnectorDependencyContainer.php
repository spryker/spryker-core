<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\PayoneOmsConnector\PayoneOmsConnectorDependencyProvider;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;

class PayoneOmsConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return PayoneFacade
     */
    public function createPayoneFacade()
    {
        return $this->getProvidedDependency(PayoneOmsConnectorDependencyProvider::FACADE_PAYONE);
    }

}
