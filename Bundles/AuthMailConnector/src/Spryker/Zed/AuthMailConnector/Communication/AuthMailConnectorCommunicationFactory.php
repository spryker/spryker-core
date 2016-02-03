<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\AuthMailConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig getConfig()
 */
class AuthMailConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @deprecated Use getMailFacade() instead.
     *
     * @return \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface
     */
    public function createMailFacade()
    {
        trigger_error('Deprecated, use getMailFacade() instead.', E_USER_DEPRECATED);

        return $this->getMailFacade();
    }

    /**
     * @return \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(AuthMailConnectorDependencyProvider::FACADE_MAIL);
    }

}
