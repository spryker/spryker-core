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
     * @deprecated Use getMailFacade() instead.
     *
     * @return AuthMailConnectorToMailInterface
     */
    public function createMailFacade()
    {
        trigger_error('Deprecated, use getMailFacade() instead.', E_USER_DEPRECATED);

        return $this->getMailFacade();
    }
    /**
     * @return AuthMailConnectorToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(AuthMailConnectorDependencyProvider::FACADE_MAIL);
    }

}
