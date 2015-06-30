<?php
/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\PayoneOmsConnector\PayoneOmsConnectorDependencyProvider;
use Symfony\Component\Validator\Validator;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;

class PayoneOmsConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return PayoneFacade
     */
    public function createPayoneFacade()
    {
        return $this->getProvidedDependency(PayoneOmsConnectorDependencyProvider::FACADE_PAYONE);
    }
}
