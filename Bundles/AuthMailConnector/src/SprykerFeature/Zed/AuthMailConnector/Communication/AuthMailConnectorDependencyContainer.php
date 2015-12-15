<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\AuthMailConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Mail\Business\MailFacade;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorDependencyProvider;

class AuthMailConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return MailFacade
     */
    public function createMailFacade()
    {
        return $this->getProvidedDependency(AuthMailConnectorDependencyProvider::FACADE_MAIL);
    }

}
