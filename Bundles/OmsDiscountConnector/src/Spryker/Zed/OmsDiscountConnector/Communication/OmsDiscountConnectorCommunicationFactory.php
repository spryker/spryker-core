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
     * @deprecated Use getDiscountFacade() instead.
     *
     * @return \Spryker\Zed\OmsDiscountConnector\Dependency\Facade\OmsDiscountConnectorToDiscountInterface
     */
    public function createDiscountFacade()
    {
        trigger_error('Deprecated, use getDiscountFacade() instead.', E_USER_DEPRECATED);

        return $this->getDiscountFacade();
    }

    /**
     * @return \Spryker\Zed\OmsDiscountConnector\Dependency\Facade\OmsDiscountConnectorToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(OmsDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
