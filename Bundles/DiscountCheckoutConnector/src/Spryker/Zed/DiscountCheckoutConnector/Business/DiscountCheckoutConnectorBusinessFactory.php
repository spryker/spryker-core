<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Business;

use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydrator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\DiscountCheckoutConnector\DiscountCheckoutConnectorDependencyProvider;
use Spryker\Zed\DiscountCheckoutConnector\DiscountCheckoutConnectorConfig;

/**
 * @method DiscountCheckoutConnectorConfig getConfig()
 */
class DiscountCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydratorInterface
     */
    public function createOrderHydrator()
    {
        return new DiscountOrderHydrator();
    }

    /**
     * @return \Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaverInterface
     */
    public function createDiscountSaver()
    {
        return new DiscountSaver(
            $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::QUERY_CONTAINER_DISCOUNT),
            $this->getDiscountFacade()
        );
    }

    /**
     * @deprecated Use getDiscountFacade() instead.
     *
     * @return \Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface
     */
    public function createDiscountFacade()
    {
        trigger_error('Deprecated, use getDiscountFacade() instead.', E_USER_DEPRECATED);

        return $this->getDiscountFacade();
    }

    /**
     * @return \Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
