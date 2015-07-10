<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\MailBusiness;
use SprykerFeature\Shared\Library\Config;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Pyz\Shared\Mail\MailConfig;

/**
 * @method MailBusiness getFactory()
 */
class MailDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return MailSenderInterface
     */
    public function getMailSender()
    {
        return $this->getFactory()->createMandrillMailSender(
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
        return $this->getFactory()->createInclusionHandler();
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
