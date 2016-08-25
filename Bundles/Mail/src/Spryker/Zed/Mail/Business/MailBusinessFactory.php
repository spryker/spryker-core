<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Mail\MailConstants;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Mail\MailConfig getConfig()
 */
class MailBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Mail\Business\MailSenderInterface
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
     * @return \Spryker\Zed\Mail\Business\InclusionHandlerInterface
     */
    protected function createInclusionHandler()
    {
        return new InclusionHandler();
    }

    /**
     * @return string
     */
    protected function getAPIKey()
    {
        return Config::get(MailConstants::MAIL_PROVIDER_MANDRILL)['api-key'];
    }

}
