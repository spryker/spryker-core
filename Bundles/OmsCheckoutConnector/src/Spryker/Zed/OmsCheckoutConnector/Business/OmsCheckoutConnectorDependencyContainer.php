<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\OmsCheckoutConnector\OmsCheckoutConnectorConfig;

/**
 * @method OmsCheckoutConnectorConfig getConfig()
 */
class OmsCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return OmsOrderHydratorInterface
     */
    public function createOmsOrderHydrator()
    {
        return new OmsOrderHydrator();
    }

}
