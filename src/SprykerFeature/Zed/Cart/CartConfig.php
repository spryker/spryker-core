<?php

namespace SprykerFeature\Zed\Cart;

use SprykerEngine\Zed\Kernel\Business;
use SprykerFeature\Zed\Cart\Dependency\Plugin\CheckAvailabilityPluginInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CartConfig extends AbstractBundleConfig
{

    /**
     * Enable or disable cart storage
     * @return bool
     */
    public function getCartStorageEnabled()
    {
        return false;
    }

    /**
     * Get the type of strategy used for merging cart item quantities
     * for guest cart and cutomer cart on login if cart storage is enabled
     * @return \SprykerFeature_Zed_Cart_Business_Model_Strategies_MergeStrategyInterface
     */
    public function getCartStorageMergeStrategy()
    {
        return $this->factory->createModelStrategiesMergeStrategyMax();
    }

    /**
     * Get the type of strategy used for removing cart items
     * @return \SprykerFeature_Zed_Cart_Business_Model_Strategies_ClearStrategyInterface
     */
    public function getCartStorageClearStrategy()
    {
        return $this->factory->createModelStrategiesClearStrategyMarkDelete();
    }

    /**
     * @return CheckAvailabilityPluginInterface
     */
    public function getCheckAvailabilityPlugin()
    {
       return Locator::getInstance()->availabilityCartConnector()->pluginCheckAvailabilityPlugin();
    }

}
