<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsDiscountConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OmsDiscountConnector\Dependency\Facade\OmsDiscountConnectorToDiscountInterface;
use Spryker\Zed\OmsDiscountConnector\OmsDiscountConnectorDependencyProvider;
use Spryker\Zed\OmsDiscountConnector\OmsDiscountConnectorConfig;

/**
 * @method OmsDiscountConnectorConfig getConfig()
 */
class OmsDiscountConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return OmsDiscountConnectorToDiscountInterface
     */
    public function createDiscountFacade()
    {
        return $this->getProvidedDependency(OmsDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
