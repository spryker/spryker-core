<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector;

use Spryker\Shared\CustomerMailConnector\CustomerMailConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerMailConnectorConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getFromEmailName()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_FROM_EMAIL_NAME);
    }

    /**
     * @return string
     */
    public function getFromEmailAddress()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_FROM_EMAIL_ADDRESS);
    }

    /**
     * @return string
     */
    public function getRegistrationToken()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_REGISTRATION_TOKEN);
    }

    /**
     * @return string
     */
    public function getRegistrationSubject()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_REGISTRATION_SUBJECT);
    }

    /**
     * @return string
     */
    public function getPasswordRestoreToken()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_PASSWORD_RESTORE_TOKEN);
    }

    /**
     * @return string
     */
    public function getPasswordRestoreSubject()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_PASSWORD_RESTORE_SUBJECT);
    }

    /**
     * @return string
     */
    public function getPasswordRestoredConfirmationToken()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_TOKEN);
    }

    /**
     * @return string
     */
    public function getPasswordRestoredConfirmationSubject()
    {
        return $this->get(CustomerMailConnectorConstants::SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_SUBJECT);
    }

    /**
     * @return string
     */
    public function getMergeLanguage()
    {
        return $this->get(CustomerMailConnectorConstants::MERGE_LANGUAGE_HANDLEBARS);
    }

}
