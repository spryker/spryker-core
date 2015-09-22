<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Customer\CustomerConfig;

class CustomerMailConnectorConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getRegistrationToken()
    {
        return $this->get(CustomerConfig::SHOP_MAIL_REGISTRATION_TOKEN);
    }

    /**
     * @return string
     */
    public function getRegistrationSubject()
    {
        return $this->get(CustomerConfig::SHOP_MAIL_REGISTRATION_SUBJECT);
    }
}
