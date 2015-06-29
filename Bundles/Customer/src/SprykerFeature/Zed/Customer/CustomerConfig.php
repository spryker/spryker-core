<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CustomerConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getPasswordRestoredConfirmationSenders()
    {
        return [
            CustomerDependencyProvider::PASSWORD_RESTORED_CONFIRMATION_SENDER
        ];
    }

    /**
     * @return array
     */
    public function getPasswordRestoreTokenSenders()
    {
        return [
            CustomerDependencyProvider::PASSWORD_RESTORE_TOKEN_SENDER
        ];
    }

    /**
     * @return array
     */
    public function getRegistrationTokenSenders()
    {
        return [
            CustomerDependencyProvider::REGISTRATION_TOKEN_SENDER
        ];
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get('HOST_YVES');
    }
}
