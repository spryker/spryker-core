<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Business;

use SprykerFeature\Shared\Library\Config;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Shared\Mail\MailConfig;

class MailDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return MailSenderInterface
     */
    public function getMailSender()
    {
        return new MandrillMailSender(
            $this->getMandrill(),
            $this->getInclusionHandler()
        );
    }

    /**
     * @return \Mandrill
     */
    protected function getMandrill()
    {
        return new \Mandrill(
            $this->getAPIKey()
        );
    }

    /**
     * @return InclusionHandlerInterface
     */
    protected function getInclusionHandler()
    {
        return new InclusionHandler();
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    protected function getAPIKey()
    {
        return Config::get(MailConfig::MAIL_PROVIDER_MANDRILL)['api-key'];
    }

}
