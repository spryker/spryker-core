<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Business;

use Spryker\Zed\AuthMailConnector\AuthMailConnectorDependencyProvider;
use Spryker\Zed\AuthMailConnector\Business\Mail\MailSender;
use Spryker\Zed\AuthMailConnector\Business\Mail\MailSenderInterface;
use Spryker\Zed\AuthMailConnector\Business\Mail\MailTransferGenerator;
use Spryker\Zed\AuthMailConnector\Business\Mail\MailTransferGeneratorInterface;
use Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig getConfig()
 */
class AuthMailConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AuthMailConnector\Business\Mail\MailSenderInterface
     */
    public function createMailSender(): MailSenderInterface
    {
        return new MailSender(
            $this->createMailTransferGenerator(),
            $this->getMailFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AuthMailConnector\Business\Mail\MailTransferGeneratorInterface
     */
    public function createMailTransferGenerator(): MailTransferGeneratorInterface
    {
        return new MailTransferGenerator(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface
     */
    public function getMailFacade(): AuthMailConnectorToMailInterface
    {
        return $this->getProvidedDependency(AuthMailConnectorDependencyProvider::FACADE_MAIL);
    }
}
