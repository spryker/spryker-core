<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Business;

use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydrator;
use Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydratorInterface;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaverInterface;
use Spryker\Zed\DiscountCheckoutConnector\DiscountCheckoutConnectorDependencyProvider;
use Spryker\Zed\DiscountCheckoutConnector\DiscountCheckoutConnectorConfig;

/**
 * @method DiscountCheckoutConnectorConfig getConfig()
 */
class DiscountCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return DiscountOrderHydratorInterface
     */
    public function createOrderHydrator()
    {
        return new DiscountOrderHydrator();
    }

    /**
     * @return DiscountSaverInterface
     */
    public function createDiscountSaver()
    {
        return new DiscountSaver(
            $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::QUERY_CONTAINER_DISCOUNT),
            $this->createDiscountFacade()
        );
    }

    /**
     * @return DiscountCheckoutConnectorToDiscountInterface
     */
    public function createDiscountFacade()
    {
        return $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
