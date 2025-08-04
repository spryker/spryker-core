<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication;

use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Activator\User\UserMultiFactorAuthActivator;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Activator\User\UserMultiFactorAuthActivatorInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Builder\Response\ResponseBuilder;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Builder\Response\ResponseBuilderInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Deactivator\User\UserMultiFactorAuthDeactivator;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Deactivator\User\UserMultiFactorAuthDeactivatorInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Form\CodeValidationForm;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Form\DataProvider\TypeSelectionFormDataProvider;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Form\TypeSelectionForm;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\Request\RequestReader;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\Request\RequestReaderInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\User\UserReader;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\User\UserReaderInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Client\MultiFactorAuthMerchantPortalToSessionClientInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToMultiFactorAuthFacadeInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToUserFacadeInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConfig getConfig()
 */
class MultiFactorAuthMerchantPortalCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToUserFacadeInterface
     */
    public function getUserFacade(): MultiFactorAuthMerchantPortalToUserFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Client\MultiFactorAuthMerchantPortalToSessionClientInterface
     */
    public function getSessionClient(): MultiFactorAuthMerchantPortalToSessionClientInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToMultiFactorAuthFacadeInterface
     */
    public function getMultiFactorAuthFacade(): MultiFactorAuthMerchantPortalToMultiFactorAuthFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::FACADE_MULTI_FACTOR_AUTH);
    }

    /**
     * @return \Symfony\Contracts\Translation\TranslatorInterface
     */
    public function getTranslatorService(): TranslatorInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::SERVICE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface>
     */
    public function getPostLoginMultiFactorAuthenticationPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function getUserMultiFactorAuthPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthMerchantPortalDependencyProvider::PLUGINS_USER_MULTI_FACTOR_AUTH);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Form\DataProvider\TypeSelectionFormDataProvider
     */
    public function createTypeSelectionFormDataProvider(): TypeSelectionFormDataProvider
    {
        return new TypeSelectionFormDataProvider(
            $this->getMultiFactorAuthFacade(),
            $this->createUserReader(),
            $this->createRequestReader(),
        );
    }

    /**
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getTypeSelectionForm(array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(TypeSelectionForm::class, null, $formOptions);
    }

    /**
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCodeValidationForm(array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(CodeValidationForm::class, null, $formOptions);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\User\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader(
            $this->getUserFacade(),
            $this->getSessionClient(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\Request\RequestReaderInterface
     */
    public function createRequestReader(): RequestReaderInterface
    {
        return new RequestReader();
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Builder\Response\ResponseBuilderInterface
     */
    public function createResponseBuilder(): ResponseBuilderInterface
    {
        return new ResponseBuilder(
            $this->getZedUiFactory(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Activator\User\UserMultiFactorAuthActivatorInterface
     */
    public function createUserMultiFactorAuthActivator(): UserMultiFactorAuthActivatorInterface
    {
        return new UserMultiFactorAuthActivator(
            $this->getMultiFactorAuthFacade(),
            $this->createRequestReader(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Deactivator\User\UserMultiFactorAuthDeactivatorInterface
     */
    public function createUserMultiFactorAuthDeactivator(): UserMultiFactorAuthDeactivatorInterface
    {
        return new UserMultiFactorAuthDeactivator(
            $this->getMultiFactorAuthFacade(),
            $this->createRequestReader(),
        );
    }
}
