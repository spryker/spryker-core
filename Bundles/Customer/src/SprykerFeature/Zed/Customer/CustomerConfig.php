<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerEngine\Shared\Kernel\Store;

class CustomerConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(SystemConfig::HOST_YVES);
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

    /**
     * @return bool
     */
    public function isDevelopmentEnvironment()
    {
        return \SprykerFeature_Shared_Library_Environment::getInstance()->isDevelopment();
    }

    /**
     * @return bool
     */
    public function isStagingEnvironment()
    {
        return \SprykerFeature_Shared_Library_Environment::getInstance()->isStaging();
    }

    /**
     * @return int
     */
    public function getMinimumCustomerNumber()
    {
        return 100;
    }

    /**
     * @return int
     */
    public function getCustomerNumberIncrementMin()
    {
        return 23;
    }

    /**
     * @return int
     */
    public function getCustomerNumberIncrementMax()
    {
        return 42;
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public function getCustomerPasswordRestoreTokenUrl($token)
    {
        return $this->getHostYves() . '/password/restore?token=' . $token;
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public function getRegisterConfirmTokenUrl($token)
    {
        return $this->getHostYves() . '/register/confirm?token=' . $token;
    }

}
