<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\OmsCheckoutConnector\OmsCheckoutConnectorConfig getConfig()
 */
class OmsCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\OmsCheckoutConnector\Business\OmsOrderHydratorInterface
     */
    public function createOmsOrderHydrator()
    {
        return new OmsOrderHydrator();
    }

}
