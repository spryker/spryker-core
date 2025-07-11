<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication;

use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MultiFactorAuth\Communication\Activator\User\UserMultiFactorAuthActivator;
use Spryker\Zed\MultiFactorAuth\Communication\Activator\User\UserMultiFactorAuthActivatorInterface;
use Spryker\Zed\MultiFactorAuth\Communication\ButtonCreator\MultiFactorAuthButtonCreator;
use Spryker\Zed\MultiFactorAuth\Communication\ButtonCreator\MultiFactorAuthButtonCreatorInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Deactivator\User\UserMultiFactorAuthDeactivator;
use Spryker\Zed\MultiFactorAuth\Communication\Deactivator\User\UserMultiFactorAuthDeactivatorInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Form\CodeValidationForm;
use Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\MerchantPortalTypeSelectionFormDataProvider;
use Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider;
use Spryker\Zed\MultiFactorAuth\Communication\Form\MerchantPortalCodeValidationForm;
use Spryker\Zed\MultiFactorAuth\Communication\Form\MerchantPortalTypeSelectionForm;
use Spryker\Zed\MultiFactorAuth\Communication\Form\Type\Extension\MultiFactorAuthHandlerExtension;
use Spryker\Zed\MultiFactorAuth\Communication\Form\Type\Extension\MultiFactorAuthValidationExtension;
use Spryker\Zed\MultiFactorAuth\Communication\Form\TypeSelectionForm;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReader;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Subscriber\MultiFactorAuthFormEventSubscriber;
use Spryker\Zed\MultiFactorAuth\Dependency\Client\MultiFactorAuthToSessionClientInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToCustomerFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthDependencyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface
     */
    public function getUserFacade(): MultiFactorAuthToUserFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::FACADE_USER);
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
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantPortalTypeSelectionForm(array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantPortalTypeSelectionForm::class, null, $formOptions);
    }

    /**
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantPortalCodeValidationForm(array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantPortalCodeValidationForm::class, null, $formOptions);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function getUserMultiFactorAuthPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_USER_MULTI_FACTOR_AUTH);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface>
     */
    public function getPostLoginMultiFactorAuthenticationPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_POST_LOGIN_MULTI_FACTOR_AUTH);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider
     */
    public function createTypeSelectionFormDataProvider(): TypeSelectionFormDataProvider
    {
        return new TypeSelectionFormDataProvider(
            $this->getRepository(),
            $this->getUserMultiFactorAuthPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\MerchantPortalTypeSelectionFormDataProvider
     */
    public function createMerchantPortalTypeSelectionFormDataProvider(): MerchantPortalTypeSelectionFormDataProvider
    {
        return new MerchantPortalTypeSelectionFormDataProvider(
            $this->getRepository(),
            $this->getUserMultiFactorAuthPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface
     */
    public function createRequestReader(): RequestReaderInterface
    {
        return new RequestReader();
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Activator\User\UserMultiFactorAuthActivatorInterface
     */
    public function createUserMultiFactorAuthActivator(): UserMultiFactorAuthActivatorInterface
    {
        return new UserMultiFactorAuthActivator(
            $this->getFacade(),
            $this->createRequestReader(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Deactivator\User\UserMultiFactorAuthDeactivatorInterface
     */
    public function createUserMultiFactorAuthDeactivator(): UserMultiFactorAuthDeactivatorInterface
    {
        return new UserMultiFactorAuthDeactivator(
            $this->getFacade(),
            $this->createRequestReader(),
        );
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface
     */
    public function getMailFacade(): MultiFactorAuthToMailFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\ButtonCreator\MultiFactorAuthButtonCreatorInterface
     */
    public function createMultiFactorAuthButtonCreator(): MultiFactorAuthButtonCreatorInterface
    {
        return new MultiFactorAuthButtonCreator(
            $this->getRepository(),
            $this->getCustomerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToCustomerFacadeInterface
     */
    public function getCustomerFacade(): MultiFactorAuthToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    public function createMultiFactorAuthValidationExtension(): FormTypeExtensionInterface
    {
        return new MultiFactorAuthValidationExtension(
            $this->getConfig(),
            $this->getRequestStackService(),
            $this->createMultiFactorAuthFormEventSubscriber(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Subscriber\MultiFactorAuthFormEventSubscriber
     */
    public function createMultiFactorAuthFormEventSubscriber(): EventSubscriberInterface
    {
        return new MultiFactorAuthFormEventSubscriber(
            $this->getFacade(),
            $this->getUserFacade(),
            $this->getTranslatorService(),
        );
    }

    /**
     * @return \Symfony\Contracts\Translation\TranslatorInterface
     */
    public function getTranslatorService(): TranslatorInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::SERVICE_TRANSLATOR);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStackService(): RequestStack
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    public function createMultiFactorAuthHandlerExtension(): FormTypeExtensionInterface
    {
        return new MultiFactorAuthHandlerExtension(
            $this->getConfig(),
            $this->getRequestStackService(),
            $this->getProvidedDependency(MultiFactorAuthDependencyProvider::TWIG_ENVIRONMENT),
            $this->getFacade(),
            $this->getUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Dependency\Client\MultiFactorAuthToSessionClientInterface
     */
    public function getSessionClient(): MultiFactorAuthToSessionClientInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }
}
