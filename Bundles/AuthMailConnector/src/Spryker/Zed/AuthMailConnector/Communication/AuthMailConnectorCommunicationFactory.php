<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\AuthMailConnector\Communication;

use Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorDependencyProvider;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig;

/**
 * @method AuthMailConnectorConfig getConfig()
 */
class AuthMailConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return AuthMailConnectorToMailInterface
     */
    public function createMailFacade()
    {
        return $this->getProvidedDependency(AuthMailConnectorDependencyProvider::FACADE_MAIL);
    }

}
