<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\UserEmailConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Mail\Business\MailFacade;
use SprykerFeature\Zed\UserEmailConnector\UserEmailConnectorDependencyProvider;

class UserEMailConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{
    /**
     * @return MailFacade
     */
    public function createMailFacade()
    {
        return $this->getProvidedDependency(UserEmailConnectorDependencyProvider::FACADE_MAIL);
    }
}
