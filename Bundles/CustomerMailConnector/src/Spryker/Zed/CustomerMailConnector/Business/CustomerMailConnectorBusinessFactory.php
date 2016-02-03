<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CustomerMailConnector\Business\Sender\PasswordRestoredConfirmationSender;
use Spryker\Zed\CustomerMailConnector\Business\Sender\PasswordRestoreTokenSender;
use Spryker\Zed\CustomerMailConnector\Business\Sender\RegistrationTokenSender;
use Spryker\Zed\CustomerMailConnector\CustomerMailConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\CustomerMailConnector\CustomerMailConnectorConfig getConfig()
 */
class CustomerMailConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CustomerMailConnector\Business\Sender\RegistrationTokenSender
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
     * @return \Spryker\Zed\CustomerMailConnector\Business\Sender\PasswordRestoreTokenSender
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
     * @return \Spryker\Zed\CustomerMailConnector\Business\Sender\PasswordRestoredConfirmationSender
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
