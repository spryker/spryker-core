<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidation;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface;
use Spryker\Yves\SessionCustomerValidation\EventListener\SaveSessionCustomerListener;
use Spryker\Yves\SessionCustomerValidation\FirewallListener\ValidateSessionCustomerListener;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

class SessionCustomerValidationFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSaveSessionCustomerEventSubscriber(): EventSubscriberInterface
    {
        return new SaveSessionCustomerListener(
            $this->getSessionCustomerSaverPlugin(),
            $this->getCustomerClient(),
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     *
     * @return \Symfony\Component\Security\Http\Firewall\AbstractListener
     */
    public function createValidateSessionCustomerListener(TokenStorageInterface $tokenStorage): AbstractListener
    {
        return new ValidateSessionCustomerListener(
            $tokenStorage,
            $this->getCustomerClient(),
            $this->getSessionCustomerValidatorPlugin(),
        );
    }

    /**
     * @return \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface
     */
    public function getSessionCustomerSaverPlugin(): SessionCustomerSaverPluginInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationDependencyProvider::PLUGIN_SESSION_CUSTOMER_SAVER);
    }

    /**
     * @return \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface
     */
    public function getSessionCustomerValidatorPlugin(): SessionCustomerValidatorPluginInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationDependencyProvider::PLUGIN_SESSION_CUSTOMER_VALIDATOR);
    }

    /**
     * @return \Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface
     */
    public function getCustomerClient(): SessionCustomerValidationToCustomerClientInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationDependencyProvider::CLIENT_CUSTOMER);
    }
}
