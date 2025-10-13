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
use Spryker\Client\Sales\SalesClientInterface;
use Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Service\SelfServicePortal\SelfServicePortalServiceInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Expander\SspAssetExpander;
use SprykerFeature\Yves\SelfServicePortal\Asset\Expander\SspAssetExpanderInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider\SspAssetFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider\SspAssetSearchFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\QuoteItemSspAssetForm;
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
use SprykerFeature\Yves\SelfServicePortal\Asset\Reader\SspAssetStorageReader;
use SprykerFeature\Yves\SelfServicePortal\Asset\Reader\SspAssetStorageReaderInterface;
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
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\AddressFormChecker;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\AddressFormCheckerInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\ShipmentTypeChecker;
use SprykerFeature\Yves\SelfServicePortal\Service\Checker\ShipmentTypeCheckerInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ProductOfferExpander;
use SprykerFeature\Yves\SelfServicePortal\Service\Expander\ProductOfferExpanderInterface;
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
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SingleAddressPerShipmentTypeAddressStepForm;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SspServiceCancelForm;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\AddressFormItemShipmentTypeGrouper;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\AddressFormItemShipmentTypeGrouperInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\ItemShipmentTypeGrouper;
use SprykerFeature\Yves\SelfServicePortal\Service\Grouper\ItemShipmentTypeGrouperInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Handler\ServiceSearchFormHandler;
use SprykerFeature\Yves\SelfServicePortal\Service\Handler\ServiceSearchFormHandlerInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Handler\SingleAddressPerShipmentTypePreSubmitHandler;
use SprykerFeature\Yves\SelfServicePortal\Service\Handler\SingleAddressPerShipmentTypePreSubmitHandlerInterface;
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
use Symfony\Component\Form\FormTypeInterface;
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

    public function getSspServiceCancelForm(?ItemTransfer $itemTransfer = null): FormInterface
    {
        $dataProvider = $this->createSspServiceCancelFormDataProvider();
        $data = $dataProvider->getData($itemTransfer);

        return $this->getFormFactory()->create(SspServiceCancelForm::class, $data);
    }

    public function createSspServiceCancelFormDataProvider(): SspServiceCancelFormDataProvider
    {
        return new SspServiceCancelFormDataProvider();
    }

    public function getServiceSearchForm(): FormInterface
    {
        $dataProvider = $this->createServiceSearchFormDataProvider();

        return $this->getFormFactory()->create(
            ServiceSearchForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions(),
        );
    }

    public function createServiceSearchFormDataProvider(): ServiceSearchFormDataProvider
    {
        return new ServiceSearchFormDataProvider(
            $this->getCustomerClient(),
            $this->getCompanyBusinessUnitClient(),
            $this->getPermissionClient(),
        );
    }

    public function createServiceSearchFormHandler(): ServiceSearchFormHandlerInterface
    {
        return new ServiceSearchFormHandler(
            $this->getCustomerClient(),
            $this->getConfig(),
        );
    }

    protected function getFormFactory(): FormFactoryInterface
    {
        $container = $this->createContainerWithProvidedDependencies();

        return $container->get(static::FORM_FACTORY);
    }

    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeStorageClient(),
            $this->getConfig(),
        );
    }

    public function createShipmentTypeChecker(): ShipmentTypeCheckerInterface
    {
        return new ShipmentTypeChecker(
            $this->getConfig(),
        );
    }

    public function createShipmentTypeOptionsProvider(): ShipmentTypeOptionsProviderInterface
    {
        return new ShipmentTypeOptionsProvider(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    public function createShipmentTypeExpander(): ShipmentTypeExpanderInterface
    {
        return new ShipmentTypeExpander(
            $this->createShipmentTypeReader(),
            $this->getStoreClient(),
        );
    }

    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander(
            $this->getProductOfferStorageClient(),
        );
    }

    public function createServicePointExpander(): ServicePointExpanderInterface
    {
        return new ServicePointExpander(
            $this->getProductOfferStorageClient(),
        );
    }

    public function createServiceDateTimeExpander(): ServiceDateTimeExpanderInterface
    {
        return new ServiceDateTimeExpander();
    }

    public function createShipmentTypeGroupSorter(): ShipmentTypeGroupSorterInterface
    {
        return new ShipmentTypeGroupSorter($this->getConfig());
    }

    public function createFileSizeFormatterExtension(): TwigExtension
    {
        return new FileSizeFormatterExtension();
    }

    public function createItemShipmentTypeGrouper(): ItemShipmentTypeGrouperInterface
    {
        return new ItemShipmentTypeGrouper(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    public function createAddressFormItemShipmentTypeGrouper(): AddressFormItemShipmentTypeGrouperInterface
    {
        return new AddressFormItemShipmentTypeGrouper(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    public function createServiceItemSchedulerForm(ItemTransfer $itemTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            ServiceItemSchedulerForm::class,
            $itemTransfer,
        );
    }

    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointSearchClient(),
            $this->getTwigEnvironment(),
        );
    }

    public function createResponse(string $searchResults): Response
    {
        return new Response($searchResults);
    }

    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader(
            $this->getSalesClient(),
            $this->getCustomerClient(),
            $this->getGlossaryStorageClient(),
            $this->getLocale(),
        );
    }

    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver(
            $this->getContainer(),
        );
    }

    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getProductOfferStorageClient(),
            $this->createShopContextResolver(),
        );
    }

    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader($this->getCompanyUserClient());
    }

    public function createTimeZoneFormatter(): TimeZoneFormatterInterface
    {
        return new TimeZoneFormatter($this->getConfig());
    }

    public function createFileSearchFilterHandler(): FileSearchFilterFormHandlerInterface
    {
        return new FileSearchFilterFormHandler(
            $this->createCompanyUserReader(),
            $this->getClient(),
            $this->createTimeZoneFormatter(),
            $this->getConfig(),
        );
    }

    public function createFileSearchFilterFormDataProvider(): FileSearchFilterFormDataProvider
    {
        return new FileSearchFilterFormDataProvider(
            $this->getConfig(),
            $this->getCompanyUserClient(),
            $this->getCompanyBusinessUnitClient(),
            $this->getClient(),
        );
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileSearchFilterForm(array $data): FormInterface
    {
        return $this->getFormFactory()->create(
            FileSearchFilterForm::class,
            $data,
            $this->createFileSearchFilterFormDataProvider()->getOptions(),
        );
    }

    public function createSspInquiryReader(): SspInquiryReaderInterface
    {
        return new SspInquiryReader(
            $this->getClient(),
            $this->getConfig(),
            $this->getStoreClient(),
        );
    }

    public function createSspInquirySearchFormHandler(): SspInquirySearchFormHandlerInterface
    {
        return new SspInquirySearchFormHandler();
    }

    public function createCreateSspInquiryFormDataToTransferMapper(): CreateSspInquiryFormDataToTransferMapperInterface
    {
        return new CreateSspInquiryFormDataToTransferMapper(
            $this->getCompanyUserClient(),
            $this->getCustomerClient(),
        );
    }

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

    public function createCreateGeneralSspInquiryFormExpander(): CreateSspInquiryFormExpanderInterface
    {
        return new CreateGeneralSspInquiryFormExpander($this->getRequestStack());
    }

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

    public function getSspInquirySearchFormDataProvider(): SspInquirySearchFormDataProvider
    {
        return new SspInquirySearchFormDataProvider(
            $this->getConfig(),
            $this->getStoreClient()->getCurrentStore()->getTimezone(),
            $this->getCompanyUserClient(),
            $this->getCompanyBusinessUnitClient(),
        );
    }

    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_REQUEST_STACK);
    }

    public function getShipmentTypeStorageClient(): ShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }

    public function getProductOfferStorageClient(): ProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_STORE);
    }

    public function getServicePointSearchClient(): ServicePointSearchClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SERVICE_POINT_SEARCH);
    }

    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::TWIG_ENVIRONMENT);
    }

    public function getGlossaryStorageClient(): GlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    public function getGlossaryClient(): GlossaryStorageClientInterface
    {
        return $this->getGlossaryStorageClient();
    }

    public function getLocale(): string
    {
        return $this->getTwigEnvironment()->getGlobals()['app']['locale'];
    }

    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_CUSTOMER);
    }

    public function getCompanyBusinessUnitClient(): CompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    public function getPermissionClient(): PermissionClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PERMISSION);
    }

    public function getSalesClient(): SalesClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SALES);
    }

    public function getCompanyUserClient(): CompanyUserClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_COMPANY_USER);
    }

    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_ROUTER);
    }

    public function getSelfServicePortalService(): SelfServicePortalServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_SELF_SERVICE_PORTAL);
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
     * @param array<mixed> $formData
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createQuoteItemSspAssetForm(array $formData = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(QuoteItemSspAssetForm::class, $formData, $options);
    }

    public function createSspAssetFormDataProvider(): SspAssetFormDataProvider
    {
        return new SspAssetFormDataProvider($this->getClient(), $this->getConfig());
    }

    public function createSspAssetFormDataToTransferMapper(): SspAssetFormDataToTransferMapperInterface
    {
        return new SspAssetFormDataToTransferMapper();
    }

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

    public function createSspAssetSearchFormDataProvider(): SspAssetSearchFormDataProvider
    {
        return new SspAssetSearchFormDataProvider(
            $this->getCompanyUserClient(),
            $this->getCompanyBusinessUnitClient(),
        );
    }

    public function createSspAssetSearchFormHandler(): SspAssetSearchFormHandlerInterface
    {
        return new SspAssetSearchFormHandler();
    }

    public function createSspAssetReader(): SspAssetReaderInterface
    {
        return new SspAssetReader(
            $this->getClient(),
            $this->getConfig(),
        );
    }

    public function createSspAssetStorageReader(): SspAssetStorageReaderInterface
    {
        return new SspAssetStorageReader($this->getClient());
    }

    public function createSspAssetExpander(): SspAssetExpanderInterface
    {
        return new SspAssetExpander();
    }

    public function createSspInquiryRestrictionHandler(): SspInquiryRestrictionHandlerInterface
    {
        return new SspInquiryRestrictionHandler(
            $this->getCustomerClient(),
            $this->getRouter(),
        );
    }

    public function createSspDashboardRestrictionHandler(): SspDashboardRestrictionHandlerInterface
    {
        return new SspDashboardRestrictionHandler(
            $this->getCustomerClient(),
            $this->getRouter(),
        );
    }

    public function createSingleAddressPerShipmentTypeAddressStepForm(): FormTypeInterface
    {
        return new SingleAddressPerShipmentTypeAddressStepForm();
    }

    public function createAddressFormChecker(): AddressFormCheckerInterface
    {
        return new AddressFormChecker($this->getConfig());
    }

    public function createSingleAddressPerShipmentTypePreSubmitHandler(): SingleAddressPerShipmentTypePreSubmitHandlerInterface
    {
        return new SingleAddressPerShipmentTypePreSubmitHandler($this->createAddressFormChecker());
    }

    public function getSelfServicePortalClient(): SelfServicePortalClientInterface
    {
        return $this->getClient();
    }
}
