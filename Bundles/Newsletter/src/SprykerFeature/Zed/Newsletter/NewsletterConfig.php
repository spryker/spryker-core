<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Newsletter\NewsletterConfig as SharedNewsletterConfig;
use SprykerFeature\Shared\System\SystemConfig;

class NewsletterConfig extends AbstractBundleConfig
{
    /**
     * @param string $token
     *
     * @return string
     */
    public function getCustomerPasswordRestoreTokenUrl($token)
    {
        return $this->getHostYves() . '/newsletter/approve?token=' . $token;
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(SystemConfig::HOST_YVES);
    }

    /**
     * @return string|null
     */
    public function getFromEmailName()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getFromEmailAddress()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getDoubleOptInConfirmationTemplateName()
    {
        return $this->get(SharedNewsletterConfig::SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_TEMPLATE_NAME);
    }

    /**
     * @return string
     */
    public function getPasswordRestoreSubject()
    {
        return $this->get(SharedNewsletterConfig::SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_SUBJECT);
    }

}
