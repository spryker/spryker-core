<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneSalesConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\PayoneSalesConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\PayoneSalesConnector\PayoneSalesConnectorDependencyProvider;

/**
 * @method PayoneSalesConnectorBusiness getFactory()
 */
class PayoneSalesConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return PayonePaymentLogReceiver
     */
    public function getPayonePaymentLogReceiver()
    {
        return $this->getFactory()->createPayonePaymentLogReceiver(
            $this->getProvidedDependency(PayoneSalesConnectorDependencyProvider::FACADE_PAYONE)
        );
    }

}
