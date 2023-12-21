<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SessionUserValidation\Communication\EventListener\SaveSessionUserListener;
use Spryker\Zed\SessionUserValidation\Communication\Extender\SecurityServiceExtender;
use Spryker\Zed\SessionUserValidation\Communication\Extender\SecurityServiceExtenderInterface;
use Spryker\Zed\SessionUserValidation\Communication\FirewallListener\ValidateSessionUserListener;
use Spryker\Zed\SessionUserValidation\Communication\Plugin\Security\ValidateSessionUserSecurityPlugin;
use Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface;
use Spryker\Zed\SessionUserValidation\SessionUserValidationDependencyProvider;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

/**
 * @method \Spryker\Zed\SessionUserValidation\SessionUserValidationConfig getConfig()
 */
class SessionUserValidationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSaveSessionUserEventSubscriber(): EventSubscriberInterface
    {
        return new SaveSessionUserListener(
            $this->getSessionUserSaverPlugin(),
            $this->getUserFacade(),
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     *
     * @return \Symfony\Component\Security\Http\Firewall\AbstractListener
     */
    public function createValidateSessionUserListener(TokenStorageInterface $tokenStorage): AbstractListener
    {
        return new ValidateSessionUserListener(
            $tokenStorage,
            $this->getUserFacade(),
            $this->getSessionUserValidatorPlugin(),
        );
    }

    /**
     * @return \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface
     */
    public function getSessionUserSaverPlugin(): SessionUserSaverPluginInterface
    {
        return $this->getProvidedDependency(SessionUserValidationDependencyProvider::PLUGIN_SESSION_USER_SAVER);
    }

    /**
     * @return \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface
     */
    public function getSessionUserValidatorPlugin(): SessionUserValidatorPluginInterface
    {
        return $this->getProvidedDependency(SessionUserValidationDependencyProvider::PLUGIN_SESSION_USER_VALIDATOR);
    }

    /**
     * @return \Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface
     */
    public function getUserFacade(): SessionUserValidationToUserFacadeInterface
    {
        return $this->getProvidedDependency(SessionUserValidationDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\SessionUserValidation\Communication\Extender\SecurityServiceExtenderInterface
     */
    public function createSecurityServiceExtender(): SecurityServiceExtenderInterface
    {
        if (class_exists(AuthenticationProviderManager::class) === true) {
            return new ValidateSessionUserSecurityPlugin();
        }

        return new SecurityServiceExtender(
            $this->getUserFacade(),
            $this->getSessionUserValidatorPlugin(),
        );
    }
}
