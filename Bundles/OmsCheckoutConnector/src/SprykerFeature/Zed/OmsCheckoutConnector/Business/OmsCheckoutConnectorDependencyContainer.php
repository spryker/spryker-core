<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\OmsCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\OmsCheckoutConnector\OmsCheckoutConnectorConfig;

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
