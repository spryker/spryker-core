<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantLoginForm;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationFailureHandler;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationSuccessHandler;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Provider\MerchantUserProvider;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\User;
use Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMessengerFacadeInterface;
use Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToSecurityFacadeInterface;
use Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig;
use Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class SecurityMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function createMerchantUserProvider(): UserProviderInterface
    {
        return new MerchantUserProvider();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLoginForm(): FormInterface
    {
        return $this->getFormFactory()->create(MerchantLoginForm::class);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function createSecurityUser(MerchantUserTransfer $merchantUserTransfer): UserInterface
    {
        return new User(
            $merchantUserTransfer,
            [SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER]
        );
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    public function createMerchantUserAuthenticationSuccessHandler(): AuthenticationSuccessHandlerInterface
    {
        return new MerchantUserAuthenticationSuccessHandler();
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface
     */
    public function createMerchantUserAuthenticationFailureHandler(): AuthenticationFailureHandlerInterface
    {
        return new MerchantUserAuthenticationFailureHandler();
    }

    /**
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): SecurityMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(SecurityMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMessengerFacadeInterface
     */
    public function getMessengerFacade(): SecurityMerchantPortalGuiToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(SecurityMerchantPortalGuiDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToSecurityFacadeInterface
     */
    public function getSecurityFacade(): SecurityMerchantPortalGuiToSecurityFacadeInterface
    {
        return $this->getProvidedDependency(SecurityMerchantPortalGuiDependencyProvider::FACADE_SECURITY);
    }
}
