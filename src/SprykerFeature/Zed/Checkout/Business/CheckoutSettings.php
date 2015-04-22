<?php

namespace SprykerFeature\Zed\Checkout\Business;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckAvailabilityPluginInterface;

class CheckoutSettings
{
    /**
     * @return CheckAvailabilityPluginInterface
     */
    public function getAvailability()
    {
        return Locator::getInstance()->availabilityCheckoutConnector()->pluginCheckAvailabilityPlugin();
    }

}
