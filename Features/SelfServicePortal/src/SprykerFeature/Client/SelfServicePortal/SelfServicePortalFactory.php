<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal;

use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\Permission\PermissionClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\DatabaseQuoteStorageStrategy;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteItemFinder;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteItemFinderInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteStorageStrategyInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteStorageStrategyProvider;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteStorageStrategyProviderInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\SessionQuoteStorageStrategy;
use SprykerFeature\Client\SelfServicePortal\Builder\PaginationConfigBuilderInterface;
use SprykerFeature\Client\SelfServicePortal\Builder\SortConfigBuilderInterface;
use SprykerFeature\Client\SelfServicePortal\Builder\SspAssetSearchPaginationConfigBuilder;
use SprykerFeature\Client\SelfServicePortal\Builder\SspAssetSearchSortConfigBuilder;
use SprykerFeature\Client\SelfServicePortal\Dashboard\Reader\CmsBlockCompanyBusinessUnitStorageReader;
use SprykerFeature\Client\SelfServicePortal\Dashboard\Reader\CmsBlockCompanyBusinessUnitStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Permission\SspAssetPermissionChecker;
use SprykerFeature\Client\SelfServicePortal\Permission\SspAssetPermissionCheckerInterface;
use SprykerFeature\Client\SelfServicePortal\ProductOffer\Checker\ProductServiceAvailabilityChecker;
use SprykerFeature\Client\SelfServicePortal\ProductOffer\Checker\ProductServiceAvailabilityCheckerInterface;
use SprykerFeature\Client\SelfServicePortal\ProductOffer\Reader\ProductOfferServiceReader;
use SprykerFeature\Client\SelfServicePortal\ProductOffer\Reader\ProductOfferServiceReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Search\Expander\SspAssetQueryExpander;
use SprykerFeature\Client\SelfServicePortal\Search\Expander\SspAssetQueryExpanderInterface;
use SprykerFeature\Client\SelfServicePortal\Search\Expander\SspAssetSearchQueryExpander;
use SprykerFeature\Client\SelfServicePortal\Search\Expander\SspAssetSearchQueryExpanderInterface;
use SprykerFeature\Client\SelfServicePortal\Search\Query\SspAssetSearchQuery;
use SprykerFeature\Client\SelfServicePortal\Search\Query\SspAssetSearchQueryInterface;
use SprykerFeature\Client\SelfServicePortal\Search\Reader\SspAssetSearchReader;
use SprykerFeature\Client\SelfServicePortal\Search\Reader\SspAssetSearchReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Search\ResultFormatter\SspAssetSearchResultFormatter;
use SprykerFeature\Client\SelfServicePortal\Search\ResultFormatter\SspAssetSearchResultFormatterInterface;
use SprykerFeature\Client\SelfServicePortal\Service\Reader\ShipmentTypeStorageReader;
use SprykerFeature\Client\SelfServicePortal\Service\Reader\ShipmentTypeStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\ShipmentType\Expander\ShipmentTypeProductViewExpander;
use SprykerFeature\Client\SelfServicePortal\ShipmentType\Expander\ShipmentTypeProductViewExpanderInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspAssetStorageMapper;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspAssetStorageMapperInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspModelStorageMapper;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspModelStorageMapperInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspAssetStorageReader;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspAssetStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspModelStorageReader;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspModelStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Zed\SelfServicePortalStub;
use SprykerFeature\Client\SelfServicePortal\Zed\SelfServicePortalStubInterface;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SelfServicePortalFactory extends AbstractFactory
{
    public function createSspAssetSearchResultFormatter(): SspAssetSearchResultFormatterInterface
    {
        return new SspAssetSearchResultFormatter();
    }

    public function createSelfServicePortalStub(): SelfServicePortalStubInterface
    {
        return new SelfServicePortalStub(
            $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_ZED_REQUEST),
        );
    }

    public function createShipmentTypeStorageReader(): ShipmentTypeStorageReaderInterface
    {
        return new ShipmentTypeStorageReader($this->getShipmentTypeStorageClient(), $this->getStoreClient());
    }

    public function createShipmentTypeProductViewExpander(): ShipmentTypeProductViewExpanderInterface
    {
        return new ShipmentTypeProductViewExpander($this->createShipmentTypeStorageReader());
    }

    public function createSspModelStorageReader(): SspModelStorageReaderInterface
    {
        return new SspModelStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService(),
            $this->createSspModelStorageMapper(),
        );
    }

    public function createSspModelStorageMapper(): SspModelStorageMapperInterface
    {
        return new SspModelStorageMapper();
    }

    public function createSspAssetStorageReader(): SspAssetStorageReaderInterface
    {
        return new SspAssetStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService(),
            $this->createSspAssetStorageMapper(),
            $this->createSspAssetPermissionChecker(),
        );
    }

    public function createSspAssetStorageMapper(): SspAssetStorageMapperInterface
    {
        return new SspAssetStorageMapper();
    }

    public function createSspAssetSearchReader(): SspAssetSearchReaderInterface
    {
        return new SspAssetSearchReader(
            $this->getSearchClient(),
            $this->getSspAssetSearchQueryPlugin(),
            $this->getCompanyUserClient(),
            $this->getSspAssetSearchQueryExpanderPlugins(),
            $this->getSspAssetSearchResultFormatterPlugins(),
        );
    }

    public function createSspAssetPermissionChecker(): SspAssetPermissionCheckerInterface
    {
        return new SspAssetPermissionChecker(
            $this->getPermissionClient(),
            $this->createSspAssetStorageMapper(),
        );
    }

    public function createCmsBlockCompanyBusinessUnitStorageReader(): CmsBlockCompanyBusinessUnitStorageReaderInterface
    {
        return new CmsBlockCompanyBusinessUnitStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStoreClient(),
            $this->getLocaleClient(),
        );
    }

    public function createSspAssetQueryExpander(): SspAssetQueryExpanderInterface
    {
        return new SspAssetQueryExpander(
            $this->createSspAssetStorageReader(),
            $this->createSspModelStorageReader(),
            $this->getCompanyUserClient(),
        );
    }

    public function createSspAssetSearchQuery(): SspAssetSearchQueryInterface
    {
        return new SspAssetSearchQuery($this->getConfig());
    }

    public function createSspAssetSearchPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        return new SspAssetSearchPaginationConfigBuilder();
    }

    public function getQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return $this->createQuoteStorageStrategyProvider()->provideStorage();
    }

    public function createQuoteStorageStrategyProvider(): QuoteStorageStrategyProviderInterface
    {
        return new QuoteStorageStrategyProvider(
            $this->getQuoteClient(),
            $this->getQuoteStorageStrategyProviders(),
        );
    }

    public function createSessionQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return new SessionQuoteStorageStrategy($this->getQuoteClient(), $this->createQuoteItemFinder());
    }

    public function createDatabaseQuoteStorageStrategy(): QuoteStorageStrategyInterface
    {
        return new DatabaseQuoteStorageStrategy(
            $this->getQuoteClient(),
            $this->createSelfServicePortalStub(),
        );
    }

    public function createSspAssetSearchSortConfigBuilder(): SortConfigBuilderInterface
    {
        return (new SspAssetSearchSortConfigBuilder())
            ->addSort($this->getConfig()->getAscendingNameSortConfigTransfer())
            ->addSort($this->getConfig()->getDescendingNameSortConfigTransfer());
    }

    public function createSspAssetSearchQueryExpander(): SspAssetSearchQueryExpanderInterface
    {
        return new SspAssetSearchQueryExpander(
            $this->getCompanyUserClient(),
            $this->getPermissionClient(),
            $this->getConfig(),
        );
    }

    public function createProductServiceAvailabilityChecker(): ProductServiceAvailabilityCheckerInterface
    {
        return new ProductServiceAvailabilityChecker(
            $this->createProductOfferServiceReader(),
            $this->getProductOfferAvailabilityStorageClient(),
            $this->getStoreClient(),
            $this->getConfig(),
        );
    }

    public function createProductOfferServiceReader(): ProductOfferServiceReaderInterface
    {
        return new ProductOfferServiceReader(
            $this->getProductOfferStorageClient(),
            $this->getConfig(),
        );
    }

    public function createQuoteItemFinder(): QuoteItemFinderInterface
    {
        return new QuoteItemFinder();
    }

    public function getSearchClient(): SearchClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SEARCH);
    }

    public function getSspAssetSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGIN_SSP_ASSET_SEARCH_QUERY);
    }

    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE);
    }

    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_LOCALE);
    }

    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_STORE);
    }

    public function getShipmentTypeStorageClient(): ShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }

    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    public function getPermissionClient(): PermissionClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PERMISSION);
    }

    public function getCompanyUserClient(): CompanyUserClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getSspAssetSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_SSP_ASSET_SEARCH_QUERY_EXPANDER);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getSspAssetSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_SSP_ASSET_SEARCH_RESULT_FORMATTER);
    }

    public function getProductOfferStorageClient(): ProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    public function getProductOfferAvailabilityStorageClient(): ProductOfferAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE);
    }

    public function getQuoteClient(): QuoteClientInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return array<\SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteStorageStrategyInterface>
     */
    public function getQuoteStorageStrategyProviders(): array
    {
        return [
            $this->createSessionQuoteStorageStrategy(),
            $this->createDatabaseQuoteStorageStrategy(),
        ];
    }
}
