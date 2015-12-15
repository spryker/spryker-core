<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Mail\Business;

use Spryker\Shared\Library\Config;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Shared\Mail\MailConstants;

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
        return Config::get(MailConstants::MAIL_PROVIDER_MANDRILL)['api-key'];
    }

}
