<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\AuthMailConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Mail\Business\MailFacade;
use SprykerFeature\Zed\AuthMailConnector\AuthMailConnectorDependencyProvider;

class AuthMailConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return MailFacade
     */
    public function creatMailFacade()
    {
        return $this->getProvidedDependency(AuthMailConnectorDependencyProvider::FACADE_MAIL);
    }

}
