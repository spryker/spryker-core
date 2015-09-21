<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector;

use Pyz\Shared\Mail\MailConfig;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CustomerMailConnectorConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getRegistrationToken()
    {
        return MailConfig::REGISTRATION_TOKEN;
    }

    /**
     * @return string
     */
    public function getRegistrationSubject()
    {
        return MailConfig::REGISTRATION_SUBJECT;
    }
}
