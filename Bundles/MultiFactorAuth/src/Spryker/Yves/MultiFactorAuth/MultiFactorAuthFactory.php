<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\MultiFactorAuth\Activator\Customer\CustomerMultiFactorAuthActivator;
use Spryker\Yves\MultiFactorAuth\Activator\Customer\CustomerMultiFactorAuthActivatorInterface;
use Spryker\Yves\MultiFactorAuth\Deactivator\Customer\CustomerMultiFactorAuthDeactivator;
use Spryker\Yves\MultiFactorAuth\Deactivator\Customer\CustomerMultiFactorAuthDeactivatorInterface;
use Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Yves\MultiFactorAuth\Form\CodeValidationForm;
use Spryker\Yves\MultiFactorAuth\Form\DataProvider\TypeSelectionFormDataProvider;
use Spryker\Yves\MultiFactorAuth\Form\Type\Extension\MultiFactorAuthHandlerExtension;
use Spryker\Yves\MultiFactorAuth\Form\Type\Extension\MultiFactorAuthValidationExtension;
use Spryker\Yves\MultiFactorAuth\Form\TypeSelectionForm;
use Spryker\Yves\MultiFactorAuth\Reader\Customer\CustomerMultiFactorAuthReader;
use Spryker\Yves\MultiFactorAuth\Reader\Customer\CustomerMultiFactorAuthReaderInterface;
use Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReader;
use Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReaderInterface;
use Spryker\Yves\MultiFactorAuth\Subscriber\MultiFactorAuthFormEventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 */
class MultiFactorAuthFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface
     */
    public function getCustomerClient(): MultiFactorAuthToCustomerClientInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function getCustomerMultiFactorAuthPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
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
     * @return \Spryker\Yves\MultiFactorAuth\Form\DataProvider\TypeSelectionFormDataProvider
     */
    public function createTypeSelectionFormDataProvider(): TypeSelectionFormDataProvider
    {
        return new TypeSelectionFormDataProvider($this->getClient());
    }

    /**
     * @return \Symfony\Contracts\Translation\TranslatorInterface
     */
    public function getTranslatorService(): TranslatorInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::SERVICE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Yves\MultiFactorAuth\Subscriber\MultiFactorAuthFormEventSubscriber
     */
    public function createMultiFactorAuthFormEventSubscriber(): EventSubscriberInterface
    {
        return new MultiFactorAuthFormEventSubscriber(
            $this->getClient(),
            $this->getCustomerClient(),
            $this->getTranslatorService(),
        );
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
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    public function createMultiFactorAuthHandlerExtension(): FormTypeExtensionInterface
    {
        return new MultiFactorAuthHandlerExtension(
            $this->getConfig(),
            $this->getRequestStackService(),
            $this->getProvidedDependency(MultiFactorAuthDependencyProvider::TWIG_ENVIRONMENT),
            $this->getClient(),
            $this->getCustomerClient(),
        );
    }

    /**
     * @return \Spryker\Yves\MultiFactorAuth\Reader\Customer\CustomerMultiFactorAuthReaderInterface
     */
    public function createCustomerMultiFactorAuthReader(): CustomerMultiFactorAuthReaderInterface
    {
        return new CustomerMultiFactorAuthReader(
            $this->getClient(),
            $this->getCustomerMultiFactorAuthPlugins(),
        );
    }

    /**
     * @return \Spryker\Yves\MultiFactorAuth\Activator\Customer\CustomerMultiFactorAuthActivatorInterface
     */
    public function createCustomerMultiFactorAuthActivator(): CustomerMultiFactorAuthActivatorInterface
    {
        return new CustomerMultiFactorAuthActivator(
            $this->getClient(),
            $this->createRequestReader(),
        );
    }

    /**
     * @return \Spryker\Yves\MultiFactorAuth\Deactivator\Customer\CustomerMultiFactorAuthDeactivator
     */
    public function createCustomerMultiFactorAuthDeactivator(): CustomerMultiFactorAuthDeactivatorInterface
    {
        return new CustomerMultiFactorAuthDeactivator(
            $this->getClient(),
            $this->createRequestReader(),
        );
    }

    /**
     * @return \Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReaderInterface
     */
    public function createRequestReader(): RequestReaderInterface
    {
        return new RequestReader();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStackService(): RequestStack
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
