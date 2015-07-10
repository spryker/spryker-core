<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CustomerConfig extends AbstractBundleConfig
{

    /**
     * @return PasswordRestoredConfirmationSenderPluginInterface[]
     */
    public function getPasswordRestoredConfirmationSenders()
    {
        return [];
    }

    /**
     * @return PasswordRestoreTokenSenderPluginInterface[]
     */
    public function getPasswordRestoreTokenSenders()
    {
        return [];
    }

    /**
     * @return RegistrationTokenSenderPluginInterface[]
     */
    public function getRegistrationTokenSenders()
    {
        return [];
    }

}
