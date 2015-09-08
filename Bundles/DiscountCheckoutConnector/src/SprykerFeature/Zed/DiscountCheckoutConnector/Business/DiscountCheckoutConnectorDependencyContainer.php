<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model\DiscountOrderHydratorInterface;

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
        return;
    }
}
