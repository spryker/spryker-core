<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Client\Permission\PermissionClientInterface;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Client\Sales\SalesClientInterface;
use Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Expander\SspAssetExpander;
use SprykerFeature\Yves\SelfServicePortal\Asset\Expander\SspAssetExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider\SspAssetFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider\SspAssetSearchFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetBusinessUnitRelationsForm;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetForm;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetSearchForm;
use SprykerFeature\Yves\SelfServicePortal\Asset\Handler\SspAssetSearchFormHandler;
use SprykerFeature\Yves\SelfServicePortal\Asset\Handler\SspAssetSearchFormHandlerInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Mapper\SspAssetFormDataToTransferMapper;
use SprykerFeature\Yves\SelfServicePortal\Asset\Mapper\SspAssetFormDataToTransferMapperInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Permission\SspAssetCustomerPermissionChecker;
use SprykerFeature\Yves\SelfServicePortal\Asset\Permission\SspAssetCustomerPermissionCheckerInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Reader\SspAssetReader;
use SprykerFeature\Yves\SelfServicePortal\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\DataProvider\FileSearchFilterFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\Handler\FileSearchFilterFormHandler;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\Handler\FileSearchFilterFormHandlerInterface;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Formatter\TimeZoneFormatter;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Yves\SelfServicePortal\Dashboard\Handler\SspDashboardRestrictionHandler;
use SprykerFeature\Yves\SelfServicePortal\Dashboard\Handler\SspDashboardRestrictionHandlerInterface;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\DataProvider\SspInquiryFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\DataProvider\SspInquirySearchFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateGeneralSspInquiryFormExpander;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateOrderSspInquiryFormExpander;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateSspAssetSspInquiryFormExpander;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateSspInquiryFormExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquiryCancelForm;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquiryForm;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquirySearchForm;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler\SspInquiryRestrictionHandler;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler\SspInquiryRestrictionHandlerInterface;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler\SspInquirySearchFormHandler;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler\SspInquirySearchFormHandlerInterface;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Mapper\CreateSspInquiryFormDataToTransferMapper;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Mapper\CreateSspInquiryFormDataToTransferMapperInterface;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Reader\SspInquiryReader;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Reader\SspInquiryReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\Reader\CompanyUserReader;
use SprykerFeature\Yves\SelfServicePortal\Reader\CompanyUserReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\ShipmentTypeChecker;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\ShipmentTypeCheckerInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ProductOfferExpander;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ProductOfferExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServiceDateTimeEnabledExpander;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServiceDateTimeEnabledExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServiceDateTimeExpander;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServiceDateTimeExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServicePointExpander;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServicePointExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ShipmentTypeExpander;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ShipmentTypeExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\DataProvider\ServiceSearchFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\DataProvider\SspServiceCancelFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\ServiceItemSchedulerForm;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\ServiceSearchForm;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SspServiceCancelForm;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\AddressFormItemShipmentTypeGrouper;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\AddressFormItemShipmentTypeGrouperInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\ItemShipmentTypeGrouper;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\ItemShipmentTypeGrouperInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Handler\ServiceSearchFormHandler;
use SprykerFeature\Yves\SelfServicePortal\Service\Handler\ServiceSearchFormHandlerInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Provider\ShipmentTypeOptionsProvider;
use SprykerFeature\Yves\SelfServicePortal\Service\Provider\ShipmentTypeOptionsProviderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\OrderReader;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\OrderReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\ProductOfferReader;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\ProductOfferReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\ServicePointReader;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\ServicePointReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\ShipmentTypeReader;
use SprykerFeature\Yves\SelfServicePortal\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Resolver\ShopContextResolver;
use SprykerFeature\Yves\SelfServicePortal\Service\Resolver\ShopContextResolverInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Sorter\ShipmentTypeGroupSorter;
use SprykerFeature\Yves\SelfServicePortal\Service\Sorter\ShipmentTypeGroupSorterInterface;
use SprykerFeature\Yves\SelfServicePortal\Twig\FileSizeFormatterExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 */
class SelfServicePortalFactory extends AbstractFactory
{
    /**
     * @var string
     */
    protected const FORM_FACTORY = 'FORM_FACTORY';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSspServiceCancelForm(?ItemTransfer $itemTransfer = null): FormInterface
    {
        $dataProvider = $this->createSspServiceCancelFormDataProvider();
        $data = $dataProvider->getData($itemTransfer);

        return $this->getFormFactory()->create(SspServiceCancelForm::class, $data);
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Form\DataProvider\SspServiceCancelFormDataProvider
     */
    public function createSspServiceCancelFormDataProvider(): SspServiceCancelFormDataProvider
    {
        return new SspServiceCancelFormDataProvider();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getServiceSearchForm(): FormInterface
    {
        $dataProvider = $this->createServiceSearchFormDataProvider();

        return $this->getFormFactory()->create(
            ServiceSearchForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Form\DataProvider\ServiceSearchFormDataProvider
     */
    public function createServiceSearchFormDataProvider(): ServiceSearchFormDataProvider
    {
        return new ServiceSearchFormDataProvider(
            $this->getCustomerClient(),
            $this->getCompanyBusinessUnitClient(),
            $this->getPermissionClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Handler\ServiceSearchFormHandlerInterface
     */
    public function createServiceSearchFormHandler(): ServiceSearchFormHandlerInterface
    {
        return new ServiceSearchFormHandler(
            $this->getCustomerClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory(): FormFactoryInterface
    {
        $container = $this->createContainerWithProvidedDependencies();

        return $container->get(static::FORM_FACTORY);
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeStorageClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Checker\ShipmentTypeCheckerInterface
     */
    public function createShipmentTypeChecker(): ShipmentTypeCheckerInterface
    {
        return new ShipmentTypeChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Provider\ShipmentTypeOptionsProviderInterface
     */
    public function createShipmentTypeOptionsProvider(): ShipmentTypeOptionsProviderInterface
    {
        return new ShipmentTypeOptionsProvider(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Expander\ShipmentTypeExpanderInterface
     */
    public function createShipmentTypeExpander(): ShipmentTypeExpanderInterface
    {
        return new ShipmentTypeExpander(
            $this->createShipmentTypeReader(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander(
            $this->getProductOfferStorageClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServicePointExpanderInterface
     */
    public function createServicePointExpander(): ServicePointExpanderInterface
    {
        return new ServicePointExpander(
            $this->getProductOfferStorageClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServiceDateTimeExpanderInterface
     */
    public function createServiceDateTimeExpander(): ServiceDateTimeExpanderInterface
    {
        return new ServiceDateTimeExpander();
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Expander\ServiceDateTimeEnabledExpanderInterface
     */
    public function createServiceDateTimeEnabledExpander(): ServiceDateTimeEnabledExpanderInterface
    {
        return new ServiceDateTimeEnabledExpander(
            $this->getProductStorageClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Sorter\ShipmentTypeGroupSorterInterface
     */
    public function createShipmentTypeGroupSorter(): ShipmentTypeGroupSorterInterface
    {
        return new ShipmentTypeGroupSorter($this->getConfig());
    }

    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createFileSizeFormatterExtension(): TwigExtension
    {
        return new FileSizeFormatterExtension();
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Grouper\ItemShipmentTypeGrouperInterface
     */
    public function createItemShipmentTypeGrouper(): ItemShipmentTypeGrouperInterface
    {
        return new ItemShipmentTypeGrouper(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Grouper\AddressFormItemShipmentTypeGrouperInterface
     */
    public function createAddressFormItemShipmentTypeGrouper(): AddressFormItemShipmentTypeGrouperInterface
    {
        return new AddressFormItemShipmentTypeGrouper(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createServiceItemSchedulerForm(ItemTransfer $itemTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            ServiceItemSchedulerForm::class,
            $itemTransfer,
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Reader\ServicePointReaderInterface
     */
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointSearchClient(),
            $this->getTwigEnvironment(),
        );
    }

    /**
     * @param string $searchResults
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createResponse(string $searchResults): Response
    {
        return new Response($searchResults);
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Reader\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader(
            $this->getSalesClient(),
            $this->getCustomerClient(),
            $this->getGlossaryStorageClient(),
            $this->getLocale(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver(
            $this->getContainer(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Service\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getProductOfferStorageClient(),
            $this->createShopContextResolver(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Reader\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader($this->getCompanyUserClient());
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\CompanyFile\Formatter\TimeZoneFormatterInterface
     */
    public function createTimeZoneFormatter(): TimeZoneFormatterInterface
    {
        return new TimeZoneFormatter($this->getConfig());
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\Handler\FileSearchFilterFormHandlerInterface
     */
    public function createFileSearchFilterHandler(): FileSearchFilterFormHandlerInterface
    {
        return new FileSearchFilterFormHandler(
            $this->createCompanyUserReader(),
            $this->getClient(),
            $this->createTimeZoneFormatter(),
            $this->getConfig(),
        );
    }

        /**
         * @return \SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\DataProvider\FileSearchFilterFormDataProvider
         */
    public function createFileSearchFilterFormDataProvider(): FileSearchFilterFormDataProvider
    {
        return new FileSearchFilterFormDataProvider($this->getConfig(), $this->getGlossaryStorageClient());
    }

    /**
     * @param array<string, mixed> $data
     * @param string $localeName
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileSearchFilterForm(array $data, string $localeName): FormInterface
    {
        return $this->getFormFactory()->create(
            FileSearchFilterForm::class,
            $data,
            $this->createFileSearchFilterFormDataProvider()->getOptions($localeName),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Reader\SspInquiryReaderInterface
     */
    public function createSspInquiryReader(): SspInquiryReaderInterface
    {
        return new SspInquiryReader(
            $this->getClient(),
            $this->getConfig(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler\SspInquirySearchFormHandlerInterface
     */
    public function createSspInquirySearchFormHandler(): SspInquirySearchFormHandlerInterface
    {
        return new SspInquirySearchFormHandler();
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Mapper\CreateSspInquiryFormDataToTransferMapperInterface
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
     * @return array<\SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateSspInquiryFormExpanderInterface>
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
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateSspInquiryFormExpanderInterface
     */
    public function createCreateGeneralSspInquiryFormExpander(): CreateSspInquiryFormExpanderInterface
    {
        return new CreateGeneralSspInquiryFormExpander($this->getRequestStack());
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateSspInquiryFormExpanderInterface
     */
    public function createCreateOrderSspInquiryFormExpander(): CreateSspInquiryFormExpanderInterface
    {
        return new CreateOrderSspInquiryFormExpander($this->getRequestStack());
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander\CreateSspAssetSspInquiryFormExpander
     */
    public function createCreateSspAssetSspInquiryFormExpander(): CreateSspInquiryFormExpanderInterface
    {
        return new CreateSspAssetSspInquiryFormExpander($this->getRequestStack());
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\DataProvider\SspInquiryFormDataProvider
     */
    public function getSspInquiryFormDataProvider(): SspInquiryFormDataProvider
    {
        return new SspInquiryFormDataProvider($this->getConfig());
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
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\DataProvider\SspInquirySearchFormDataProvider
     */
    public function getSspInquirySearchFormDataProvider(): SspInquirySearchFormDataProvider
    {
        return new SspInquirySearchFormDataProvider($this->getConfig(), $this->getStoreClient()->getCurrentStore()->getTimezone());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface
     */
    public function getProductOfferStorageClient(): ProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface
     */
    public function getServicePointSearchClient(): ServicePointSearchClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SERVICE_POINT_SEARCH);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::TWIG_ENVIRONMENT);
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): GlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getTwigEnvironment()->getGlobals()['app']['locale'];
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): CompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Client\Permission\PermissionClientInterface
     */
    public function getPermissionClient(): PermissionClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PERMISSION);
    }

    /**
     * @return \Spryker\Client\Sales\SalesClientInterface
     */
    public function getSalesClient(): SalesClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SALES);
    }

    /**
     * @return \Spryker\Client\CompanyUser\CompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUserClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_COMPANY_USER);
    }

     /**
      * @return \Spryker\Service\FileManager\FileManagerServiceInterface
      */
    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer|null $sspAssetTransfer
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAssetForm(?SspAssetTransfer $sspAssetTransfer = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetForm::class, $sspAssetTransfer, $options);
    }

    /**
     * @param array<mixed> $formData
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSspAssetBusinessUnitRelationsForm(array $formData = []): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetBusinessUnitRelationsForm::class, $formData);
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider\SspAssetFormDataProvider
     */
    public function createSspAssetFormDataProvider(): SspAssetFormDataProvider
    {
        return new SspAssetFormDataProvider($this->getClient(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Asset\Mapper\SspAssetFormDataToTransferMapperInterface
     */
    public function createSspAssetFormDataToTransferMapper(): SspAssetFormDataToTransferMapperInterface
    {
        return new SspAssetFormDataToTransferMapper();
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Asset\Permission\SspAssetCustomerPermissionCheckerInterface
     */
    public function createSspAssetCustomerPermissionChecker(): SspAssetCustomerPermissionCheckerInterface
    {
        return new SspAssetCustomerPermissionChecker();
    }

    /**
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSspAssetSearchForm(array $options): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetSearchForm::class, [], $options);
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider\SspAssetSearchFormDataProvider
     */
    public function createSspAssetSearchFormDataProvider(): SspAssetSearchFormDataProvider
    {
        return new SspAssetSearchFormDataProvider();
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Asset\Handler\SspAssetSearchFormHandlerInterface
     */
    public function createSspAssetSearchFormHandler(): SspAssetSearchFormHandlerInterface
    {
        return new SspAssetSearchFormHandler();
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Asset\Reader\SspAssetReaderInterface
     */
    public function createSspAssetReader(): SspAssetReaderInterface
    {
        return new SspAssetReader(
            $this->getClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Asset\Expander\SspAssetExpanderInterface
     */
    public function createSspAssetExpander(): SspAssetExpanderInterface
    {
        return new SspAssetExpander();
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler\SspInquiryRestrictionHandlerInterface
     */
    public function createSspInquiryRestrictionHandler(): SspInquiryRestrictionHandlerInterface
    {
        return new SspInquiryRestrictionHandler(
            $this->getCustomerClient(),
            $this->getRouter(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SelfServicePortal\Dashboard\Handler\SspDashboardRestrictionHandlerInterface
     */
    public function createSspDashboardRestrictionHandler(): SspDashboardRestrictionHandlerInterface
    {
        return new SspDashboardRestrictionHandler(
            $this->getCustomerClient(),
            $this->getRouter(),
        );
    }
}
