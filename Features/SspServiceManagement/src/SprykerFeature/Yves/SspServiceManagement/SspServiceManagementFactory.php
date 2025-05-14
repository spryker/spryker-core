<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Client\Permission\PermissionClientInterface;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Client\Sales\SalesClientInterface;
use Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerFeature\Yves\SspServiceManagement\Checker\ShipmentTypeChecker;
use SprykerFeature\Yves\SspServiceManagement\Checker\ShipmentTypeCheckerInterface;
use SprykerFeature\Yves\SspServiceManagement\Expander\ProductOfferExpander;
use SprykerFeature\Yves\SspServiceManagement\Expander\ProductOfferExpanderInterface;
use SprykerFeature\Yves\SspServiceManagement\Expander\ServiceDateTimeEnabledExpander;
use SprykerFeature\Yves\SspServiceManagement\Expander\ServiceDateTimeEnabledExpanderInterface;
use SprykerFeature\Yves\SspServiceManagement\Expander\ServiceDateTimeExpander;
use SprykerFeature\Yves\SspServiceManagement\Expander\ServiceDateTimeExpanderInterface;
use SprykerFeature\Yves\SspServiceManagement\Expander\ServicePointExpander;
use SprykerFeature\Yves\SspServiceManagement\Expander\ServicePointExpanderInterface;
use SprykerFeature\Yves\SspServiceManagement\Expander\ShipmentTypeExpander;
use SprykerFeature\Yves\SspServiceManagement\Expander\ShipmentTypeExpanderInterface;
use SprykerFeature\Yves\SspServiceManagement\Form\DataProvider\ServiceSearchFormDataProvider;
use SprykerFeature\Yves\SspServiceManagement\Form\DataProvider\SspServiceCancelFormDataProvider;
use SprykerFeature\Yves\SspServiceManagement\Form\ServiceItemSchedulerForm;
use SprykerFeature\Yves\SspServiceManagement\Form\ServiceSearchForm;
use SprykerFeature\Yves\SspServiceManagement\Form\SspServiceCancelForm;
use SprykerFeature\Yves\SspServiceManagement\Grouper\AddressFormItemShipmentTypeGrouper;
use SprykerFeature\Yves\SspServiceManagement\Grouper\AddressFormItemShipmentTypeGrouperInterface;
use SprykerFeature\Yves\SspServiceManagement\Grouper\ItemShipmentTypeGrouper;
use SprykerFeature\Yves\SspServiceManagement\Grouper\ItemShipmentTypeGrouperInterface;
use SprykerFeature\Yves\SspServiceManagement\Handler\ServiceSearchFormHandler;
use SprykerFeature\Yves\SspServiceManagement\Handler\ServiceSearchFormHandlerInterface;
use SprykerFeature\Yves\SspServiceManagement\Provider\ShipmentTypeOptionsProvider;
use SprykerFeature\Yves\SspServiceManagement\Provider\ShipmentTypeOptionsProviderInterface;
use SprykerFeature\Yves\SspServiceManagement\Reader\OrderReader;
use SprykerFeature\Yves\SspServiceManagement\Reader\OrderReaderInterface;
use SprykerFeature\Yves\SspServiceManagement\Reader\ProductOfferReader;
use SprykerFeature\Yves\SspServiceManagement\Reader\ProductOfferReaderInterface;
use SprykerFeature\Yves\SspServiceManagement\Reader\ServicePointReader;
use SprykerFeature\Yves\SspServiceManagement\Reader\ServicePointReaderInterface;
use SprykerFeature\Yves\SspServiceManagement\Reader\ShipmentTypeReader;
use SprykerFeature\Yves\SspServiceManagement\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Yves\SspServiceManagement\Resolver\ShopContextResolver;
use SprykerFeature\Yves\SspServiceManagement\Resolver\ShopContextResolverInterface;
use SprykerFeature\Yves\SspServiceManagement\Sorter\ShipmentTypeGroupSorter;
use SprykerFeature\Yves\SspServiceManagement\Sorter\ShipmentTypeGroupSorterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Client\SspServiceManagement\SspServiceManagementClientInterface getClient()
 */
class SspServiceManagementFactory extends AbstractFactory
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
     * @return \SprykerFeature\Yves\SspServiceManagement\Form\DataProvider\SspServiceCancelFormDataProvider
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
     * @return \SprykerFeature\Yves\SspServiceManagement\Form\DataProvider\ServiceSearchFormDataProvider
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
     * @return \SprykerFeature\Yves\SspServiceManagement\Handler\ServiceSearchFormHandlerInterface
     */
    public function createServiceSearchFormHandler(): ServiceSearchFormHandlerInterface
    {
        return new ServiceSearchFormHandler(
            $this->getCustomerClient(),
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
     * @return \SprykerFeature\Yves\SspServiceManagement\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeStorageClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Checker\ShipmentTypeCheckerInterface
     */
    public function createShipmentTypeChecker(): ShipmentTypeCheckerInterface
    {
        return new ShipmentTypeChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Provider\ShipmentTypeOptionsProviderInterface
     */
    public function createShipmentTypeOptionsProvider(): ShipmentTypeOptionsProviderInterface
    {
        return new ShipmentTypeOptionsProvider(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Expander\ShipmentTypeExpanderInterface
     */
    public function createShipmentTypeExpander(): ShipmentTypeExpanderInterface
    {
        return new ShipmentTypeExpander(
            $this->createShipmentTypeReader(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander(
            $this->getProductOfferStorageClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Expander\ServicePointExpanderInterface
     */
    public function createServicePointExpander(): ServicePointExpanderInterface
    {
        return new ServicePointExpander(
            $this->getProductOfferStorageClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Expander\ServiceDateTimeExpanderInterface
     */
    public function createServiceDateTimeExpander(): ServiceDateTimeExpanderInterface
    {
        return new ServiceDateTimeExpander();
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Expander\ServiceDateTimeEnabledExpanderInterface
     */
    public function createServiceDateTimeEnabledExpander(): ServiceDateTimeEnabledExpanderInterface
    {
        return new ServiceDateTimeEnabledExpander(
            $this->getProductStorageClient(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Sorter\ShipmentTypeGroupSorterInterface
     */
    public function createShipmentTypeGroupSorter(): ShipmentTypeGroupSorterInterface
    {
        return new ShipmentTypeGroupSorter($this->getConfig());
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Grouper\ItemShipmentTypeGrouperInterface
     */
    public function createItemShipmentTypeGrouper(): ItemShipmentTypeGrouperInterface
    {
        return new ItemShipmentTypeGrouper(
            $this->getConfig(),
            $this->createShipmentTypeGroupSorter(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Grouper\AddressFormItemShipmentTypeGrouperInterface
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
     * @return \SprykerFeature\Yves\SspServiceManagement\Reader\ServicePointReaderInterface
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
     * @return \SprykerFeature\Yves\SspServiceManagement\Reader\OrderReaderInterface
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
     * @return \SprykerFeature\Yves\SspServiceManagement\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver(
            $this->getContainer(),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspServiceManagement\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getProductOfferStorageClient(),
            $this->createShopContextResolver(),
        );
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface
     */
    public function getProductOfferStorageClient(): ProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface
     */
    public function getServicePointSearchClient(): ServicePointSearchClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_SERVICE_POINT_SEARCH);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::TWIG_ENVIRONMENT);
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): GlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_GLOSSARY_STORAGE);
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
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

        /**
         * @return \Spryker\Client\Customer\CustomerClientInterface
         */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): CompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Client\Permission\PermissionClientInterface
     */
    public function getPermissionClient(): PermissionClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_PERMISSION);
    }

    /**
     * @return \Spryker\Client\Sales\SalesClientInterface
     */
    public function getSalesClient(): SalesClientInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::CLIENT_SALES);
    }
}
