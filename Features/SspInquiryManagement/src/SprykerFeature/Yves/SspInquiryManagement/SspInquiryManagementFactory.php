<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement;

use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerFeature\Client\SspInquiryManagement\SspInquiryManagementClientInterface;
use SprykerFeature\Yves\SspInquiryManagement\Form\DataProvider\SspInquiryFormDataProvider;
use SprykerFeature\Yves\SspInquiryManagement\Form\DataProvider\SspInquirySearchFormDataProvider;
use SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateGeneralSspInquiryFormExpander;
use SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateOrderSspInquiryFormExpander;
use SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateSspAssetSspInquiryFormExpander;
use SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateSspInquiryFormExpanderInterface;
use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquiryCancelForm;
use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquiryForm;
use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquirySearchForm;
use SprykerFeature\Yves\SspInquiryManagement\Handler\SspInquirySearchFormHandler;
use SprykerFeature\Yves\SspInquiryManagement\Handler\SspInquirySearchFormHandlerInterface;
use SprykerFeature\Yves\SspInquiryManagement\Mapper\CreateSspInquiryFormDataToTransferMapper;
use SprykerFeature\Yves\SspInquiryManagement\Mapper\CreateSspInquiryFormDataToTransferMapperInterface;
use SprykerFeature\Yves\SspInquiryManagement\Reader\SspInquiryReader;
use SprykerFeature\Yves\SspInquiryManagement\Reader\SspInquiryReaderInterface;
use SprykerFeature\Yves\SspInquiryManagement\Twig\BytesExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Reader\SspInquiryReaderInterface
     */
    public function createSspInquiryReader(): SspInquiryReaderInterface
    {
        return new SspInquiryReader(
            $this->getSspInquiryClient(),
            $this->getConfig(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Handler\SspInquirySearchFormHandlerInterface
     */
    public function createSspInquirySearchFormHandler(): SspInquirySearchFormHandlerInterface
    {
        return new SspInquirySearchFormHandler();
    }

    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Mapper\CreateSspInquiryFormDataToTransferMapperInterface
     */
    public function createCreateSspInquiryFormDataToTransferMapper(): CreateSspInquiryFormDataToTransferMapperInterface
    {
        return new CreateSspInquiryFormDataToTransferMapper(
            $this->getCompanyUserClient(),
            $this->getStoreClient(),
            $this->getCustomerClient(),
        );
    }

    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createBytesExtension(): TwigExtension
    {
        return new BytesExtension();
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSspInquiryCancelForm(): FormInterface
    {
        return $this->getFormFactory()->create(SspInquiryCancelForm::class);
    }

    /**
     * @param array<mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSspInquiryForm(array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(SspInquiryForm::class, [], $formOptions);
    }

    /**
     * @return array<\SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateSspInquiryFormExpanderInterface>
     */
    public function getSspInquiryFormExpanders(): array
    {
        return [
            $this->createCreateGeneralSspInquiryFormExpander(),
            $this->createCreateOrderSspInquiryFormExpander(),
            $this->createCreateSspAssetSspInquiryFormExpander(),
        ];
    }

    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateSspInquiryFormExpanderInterface
     */
    public function createCreateGeneralSspInquiryFormExpander(): CreateSspInquiryFormExpanderInterface
    {
        return new CreateGeneralSspInquiryFormExpander($this->getRequestStack());
    }

    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateSspInquiryFormExpanderInterface
     */
    public function createCreateOrderSspInquiryFormExpander(): CreateSspInquiryFormExpanderInterface
    {
        return new CreateOrderSspInquiryFormExpander($this->getRequestStack());
    }

    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Form\Expander\CreateSspAssetSspInquiryFormExpander
     */
    public function createCreateSspAssetSspInquiryFormExpander(): CreateSspInquiryFormExpanderInterface
    {
        return new CreateSspAssetSspInquiryFormExpander($this->getRequestStack());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Form\DataProvider\SspInquiryFormDataProvider
     */
    public function getSspInquiryFormDataProvider(): SspInquiryFormDataProvider
    {
        return new SspInquiryFormDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerFeature\Client\SspInquiryManagement\SspInquiryManagementClientInterface
     */
    public function getSspInquiryClient(): SspInquiryManagementClientInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::CLIENT_SSP_INQUIRY);
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\CompanyUser\CompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUserClientInterface
    {
         return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @param array<mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSspInquirySearchForm(array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(
            SspInquirySearchForm::class,
            [],
            $formOptions,
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspInquiryManagement\Form\DataProvider\SspInquirySearchFormDataProvider
     */
    public function getSspInquirySearchFormDataProvider(): SspInquirySearchFormDataProvider
    {
        return new SspInquirySearchFormDataProvider($this->getConfig(), $this->getStoreClient()->getCurrentStore()->getTimezone());
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @return \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::SERVICE_FILE_MANAGER);
    }
}
