<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CustomerMailConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\CustomerMailConnector\Business\Sender\PasswordRestoredConfirmationSender;
use SprykerFeature\Zed\CustomerMailConnector\Business\Sender\PasswordRestoreTokenSender;
use SprykerFeature\Zed\CustomerMailConnector\Business\Sender\RegistrationTokenSender;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorConfig;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorDependencyProvider;

/**
 * @method CustomerMailConnectorBusiness getFactory()
 * @method CustomerMailConnectorConfig getConfig()
 */
class CustomerMailConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return RegistrationTokenSender
     */
    public function createRegistrationTokenSender()
    {
        return $this->getFactory()->createSenderRegistrationTokenSender(
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
        return $this->getFactory()->createSenderPasswordRestoreTokenSender(
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
        return $this->getFactory()->createSenderPasswordRestoredConfirmationSender(
            $this->getConfig(),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_MAIL),
            $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_GLOSSARY)
        );
    }

}
