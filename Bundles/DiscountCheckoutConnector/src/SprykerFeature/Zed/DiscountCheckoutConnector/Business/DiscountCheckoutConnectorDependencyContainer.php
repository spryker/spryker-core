<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\DiscountCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
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
        return $this->getFactory()->createModelDiscountOrderHydrator();
    }

    /**
     * @return DiscountSaverInterface
     */
    public function createDiscountSaver()
    {
        return $this->getFactory()->createModelDiscountSaver(
            $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::QUERY_CONTAINER_DISCOUNT)
        );
    }

}
