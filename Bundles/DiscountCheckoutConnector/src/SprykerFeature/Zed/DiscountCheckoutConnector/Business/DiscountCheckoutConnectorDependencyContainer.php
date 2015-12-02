<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business;

use SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaver;
use SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydrator;
use Generated\Zed\Ide\FactoryAutoCompletion\DiscountCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydratorInterface;
use SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model\DiscountSaverInterface;
use SprykerFeature\Zed\DiscountCheckoutConnector\DiscountCheckoutConnectorDependencyProvider;

/**
 * @method DiscountCheckoutConnectorBusiness getFactory()
 */
class DiscountCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
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
