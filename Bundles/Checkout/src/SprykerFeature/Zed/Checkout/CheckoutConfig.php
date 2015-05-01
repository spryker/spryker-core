<?php

namespace SprykerFeature\Zed\Checkout;

use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckAvailabilityPluginInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CheckoutConfig extends AbstractBundleConfig
{

    /**
     * @return CheckAvailabilityPluginInterface
     */
    public function getAvailability()
    {
        return $this->getLocator()->availabilityCheckoutConnector()->pluginCheckAvailabilityPlugin();
    }

}
