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
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerFeature\Client\SelfServicePortal\Builder\PaginationConfigBuilderInterface;
use SprykerFeature\Client\SelfServicePortal\Builder\SortConfigBuilderInterface;
use SprykerFeature\Client\SelfServicePortal\Builder\SspAssetSearchPaginationConfigBuilder;
use SprykerFeature\Client\SelfServicePortal\Builder\SspAssetSearchSortConfigBuilder;
use SprykerFeature\Client\SelfServicePortal\Dashboard\Reader\CmsBlockCompanyBusinessUnitStorageReader;
use SprykerFeature\Client\SelfServicePortal\Dashboard\Reader\CmsBlockCompanyBusinessUnitStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Permission\SspAssetPermissionChecker;
use SprykerFeature\Client\SelfServicePortal\Permission\SspAssetPermissionCheckerInterface;
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
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getSspAssetSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_SSP_ASSET_SEARCH_QUERY_EXPANDER);
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getSspAssetSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_SSP_ASSET_SEARCH_RESULT_FORMATTER);
    }

    public function createSspAssetSearchPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        $sspAssetSearchPaginationConfigBuilder = new SspAssetSearchPaginationConfigBuilder();
        $sspAssetSearchPaginationConfigBuilder->setPaginationConfigTransfer(
            $this->getConfig()->getSspAssetSearchPaginationConfigTransfer(),
        );

        return $sspAssetSearchPaginationConfigBuilder;
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
}
