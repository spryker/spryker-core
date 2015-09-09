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
        return $this->getFactory()->createModelDiscountOrderHydrator(
            $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::FACADE_DISCOUNT)
        );
    }
    /**
     * @return DiscountSaverInterface
     */
    public function createDicountSaver()
    {
        return $this->getFactory()->createModelDiscountSaver(
            $this->getProvidedDependency(DiscountCheckoutConnectorDependencyProvider::FACADE_DISCOUNT)
        );
    }

}
