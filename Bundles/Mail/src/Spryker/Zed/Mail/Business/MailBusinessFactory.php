<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Mail\Business;

use Spryker\Shared\Library\Config;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Shared\Mail\MailConstants;
use Spryker\Zed\Mail\MailConfig;

/**
 * @method MailConfig getConfig()
 */
class MailBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return MailSenderInterface
     */
    public function createMailSender()
    {
        return new MandrillMailSender(
            $this->createMandrill(),
            $this->createInclusionHandler()
        );
    }

    /**
     * @return \Mandrill
     */
    protected function createMandrill()
    {
        return new \Mandrill(
            $this->getAPIKey()
        );
    }

    /**
     * @return InclusionHandlerInterface
     */
    protected function createInclusionHandler()
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
