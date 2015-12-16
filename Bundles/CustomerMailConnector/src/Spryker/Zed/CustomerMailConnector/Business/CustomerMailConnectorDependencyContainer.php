<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CustomerMailConnector\Business\Sender\PasswordRestoredConfirmationSender;
use Spryker\Zed\CustomerMailConnector\Business\Sender\PasswordRestoreTokenSender;
use Spryker\Zed\CustomerMailConnector\Business\Sender\RegistrationTokenSender;
use Spryker\Zed\CustomerMailConnector\CustomerMailConnectorConfig;
use Spryker\Zed\CustomerMailConnector\CustomerMailConnectorDependencyProvider;

/**
 * @method CustomerMailConnectorConfig getConfig()
 */
class CustomerMailConnectorDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return RegistrationTokenSender
     */
    public function createRegistrationTokenSender()
    {
        return new RegistrationTokenSender(
            $this->getConfig(),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_MAIL),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_GLOSSARY)
        );
    }

    /**
     * @return PasswordRestoreTokenSender
     */
    public function createPasswordRestoreTokenSender()
    {
        return new PasswordRestoreTokenSender(
            $this->getConfig(),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_MAIL),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_GLOSSARY)
        );
    }

    /**
     * @return PasswordRestoredConfirmationSender
     */
    public function createPasswordRestoredConfirmationSender()
    {
        return new PasswordRestoredConfirmationSender(
            $this->getConfig(),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_MAIL),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_GLOSSARY)
        );
    }

}
