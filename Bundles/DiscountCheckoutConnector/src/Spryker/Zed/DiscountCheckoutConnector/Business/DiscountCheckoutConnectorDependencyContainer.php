<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Business;

use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydrator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydratorInterface;
use Spryker\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaverInterface;
use Spryker\Zed\DiscountCheckoutConnector\DiscountCheckoutConnectorDependencyProvider;

class DiscountCheckoutConnectorDependencyContainer extends AbstractBusinessFactory
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
     * @return DiscountFacade
     */
    public function createDiscountFacade()
    {
        return $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
