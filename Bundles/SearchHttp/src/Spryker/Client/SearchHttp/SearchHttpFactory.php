<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorFactory;
use Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorFactoryInterface;
use Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilder;
use Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface;
use Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilder;
use Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface;
use Spryker\Client\SearchHttp\Api\Decoder\SearchResponseDecoder;
use Spryker\Client\SearchHttp\Api\Decoder\SearchResponseDecoderInterface;
use Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatter;
use Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatterInterface;
use Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapper;
use Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface;
use Spryker\Client\SearchHttp\Api\SearchHttpApiClient;
use Spryker\Client\SearchHttp\Api\SearchHttpApiInterface;
use Spryker\Client\SearchHttp\Api\Sender\RequestSenderInterface;
use Spryker\Client\SearchHttp\Api\Sender\SearchRequestSender;
use Spryker\Client\SearchHttp\ApplicabilityChecker\QueryApplicabilityChecker;
use Spryker\Client\SearchHttp\ApplicabilityChecker\QueryApplicabilityCheckerInterface;
use Spryker\Client\SearchHttp\Builder\FacetConfigBuilder;
use Spryker\Client\SearchHttp\Builder\FacetConfigBuilderInterface;
use Spryker\Client\SearchHttp\Config\FacetConfig;
use Spryker\Client\SearchHttp\Config\FacetConfigInterface;
use Spryker\Client\SearchHttp\Config\PaginationConfig;
use Spryker\Client\SearchHttp\Config\PaginationConfigInterface;
use Spryker\Client\SearchHttp\Config\SearchConfigBuilder;
use Spryker\Client\SearchHttp\Config\SearchConfigBuilderInterface;
use Spryker\Client\SearchHttp\Config\SearchConfigInterface;
use Spryker\Client\SearchHttp\Config\SortConfig;
use Spryker\Client\SearchHttp\Config\SortConfigInterface;
use Spryker\Client\SearchHttp\CountProvider\SearchResultCountProvider;
use Spryker\Client\SearchHttp\CountProvider\SearchResultCountProviderInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCustomerClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToKernelAppClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToProductStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToSynchronizationServiceInterface;
use Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceInterface;
use Spryker\Client\SearchHttp\Formatter\ProductConcreteCatalogSearchHttpResultFormatter;
use Spryker\Client\SearchHttp\Formatter\ProductConcreteCatalogSearchHttpResultFormatterInterface;
use Spryker\Client\SearchHttp\Mapper\ConfigMapper;
use Spryker\Client\SearchHttp\Mapper\ConfigMapperInterface;
use Spryker\Client\SearchHttp\Mapper\ResultProductMapper;
use Spryker\Client\SearchHttp\Mapper\ResultProductMapperInterface;
use Spryker\Client\SearchHttp\Reader\ConfigReader;
use Spryker\Client\SearchHttp\Reader\ConfigReaderInterface;
use Spryker\Client\SearchHttp\Transformer\Factory\FacetValueTransformerFactory;
use Spryker\Client\SearchHttp\Transformer\Factory\FacetValueTransformerFactoryInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchHttpFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface
     */
    public function createConfigReader(): ConfigReaderInterface
    {
        return new ConfigReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createConfigMapper(),
        );
    }

    /**
     * @return \Spryker\Client\SearchHttp\Formatter\ProductConcreteCatalogSearchHttpResultFormatterInterface
     */
    public function createProductConcreteCatalogSearchHttpResultFormatter(): ProductConcreteCatalogSearchHttpResultFormatterInterface
    {
        return new ProductConcreteCatalogSearchHttpResultFormatter();
    }

    /**
     * @return \Spryker\Client\SearchHttp\CountProvider\SearchResultCountProviderInterface
     */
    public function createSearchResultCountProvider(): SearchResultCountProviderInterface
    {
        return new SearchResultCountProvider();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface
     */
    public function getStorageClient(): SearchHttpToStorageClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Mapper\ConfigMapperInterface
     */
    public function createConfigMapper(): ConfigMapperInterface
    {
        return new ConfigMapper();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    public function getStoreClient(): SearchHttpToStoreClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Config\SearchConfigInterface
     */
    public function getSearchConfig(): SearchConfigInterface
    {
        return $this->createSearchConfigBuilder()->build();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Config\SearchConfigBuilderInterface
     */
    public function createSearchConfigBuilder(): SearchConfigBuilderInterface
    {
        $searchConfigBuilder = new SearchConfigBuilder(
            $this->createFacetConfig(),
            $this->createSortConfig(),
            $this->createPaginationConfig(),
        );

        $searchConfigBuilder->setSearchConfigBuilderPlugins(
            $this->getSearchConfigBuilderPlugins(),
        );
        $searchConfigBuilder->setSearchConfigExpanderPlugins(
            $this->getSearchConfigExpanderPlugins(),
        );

        return $searchConfigBuilder;
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface>
     */
    public function getSearchConfigBuilderPlugins(): array
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::PLUGINS_SEARCH_CONFIG_BUILDER);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface>
     */
    public function getSearchConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::PLUGINS_SEARCH_CONFIG_EXPANDER);
    }

    /**
     * @return array<\Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface>
     */
    public function getFacetConfigTransferBuilderPlugins(): array
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::PLUGIN_FACET_CONFIG_TRANSFER_BUILDERS);
    }

    /**
     * @return array<\Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface>
     */
    public function getSortConfigTransferBuilderPlugins(): array
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::PLUGIN_SORT_CONFIG_TRANSFER_BUILDERS);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface
     */
    public function getLocaleClient(): SearchHttpToLocaleClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Config\FacetConfigInterface
     */
    public function createFacetConfig(): FacetConfigInterface
    {
        return new FacetConfig();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Config\SortConfigInterface
     */
    public function createSortConfig(): SortConfigInterface
    {
        return new SortConfig();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Config\PaginationConfigInterface
     */
    public function createPaginationConfig(): PaginationConfigInterface
    {
        return new PaginationConfig();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Transformer\Factory\FacetValueTransformerFactoryInterface
     */
    public function createFacetValueTransformerFactory(): FacetValueTransformerFactoryInterface
    {
        return new FacetValueTransformerFactory();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToProductStorageClientInterface
     */
    public function getProductStorageClient(): SearchHttpToProductStorageClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface
     */
    public function getMoneyClient(): SearchHttpToMoneyClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_MONEY);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): SearchHttpToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \Spryker\Client\SearchHttp\ApplicabilityChecker\QueryApplicabilityCheckerInterface
     */
    public function createQueryApplicabilityChecker(): QueryApplicabilityCheckerInterface
    {
        return new QueryApplicabilityChecker(
            $this->createConfigReader(),
        );
    }

    /**
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorFactoryInterface
     */
    public function createAggregationExtractorFactory(): AggregationExtractorFactoryInterface
    {
        return new AggregationExtractorFactory(
            $this->getMoneyClient(),
            $this->getCategoryStorageClient(),
            $this->getLocaleClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\SearchHttpApiInterface
     */
    public function createSearchApiClient(): SearchHttpApiInterface
    {
        return new SearchHttpApiClient(
            $this->createConfigReader(),
            $this->createRequestSender(),
            $this->createSearchResponseDecoder(),
            $this->createHttpResponseFormatter(),
        );
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\Sender\RequestSenderInterface
     */
    public function createRequestSender(): RequestSenderInterface
    {
        return new SearchRequestSender(
            $this->createKernelAppClient(),
            $this->createSearchHeaderBuilder(),
            $this->createSearchQueryBuilder(),
        );
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\Decoder\SearchResponseDecoderInterface
     */
    public function createSearchResponseDecoder(): SearchResponseDecoderInterface
    {
        return new SearchResponseDecoder(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatterInterface
     */
    public function createHttpResponseFormatter(): SearchResponseFormatterInterface
    {
        return new SearchResponseFormatter($this->createSearchHttpResponseTransferMapper());
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface
     */
    public function createSearchHttpResponseTransferMapper(): SearchHttpResponseTransferMapperInterface
    {
        return new SearchHttpResponseTransferMapper();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToKernelAppClientInterface
     */
    public function createKernelAppClient(): SearchHttpToKernelAppClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_KERNEL_APP);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface
     */
    public function createSearchHeaderBuilder(): SearchHeaderBuilderInterface
    {
        return new SearchHeaderBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface
     */
    public function createSearchQueryBuilder(): SearchQueryBuilderInterface
    {
        return new SearchQueryBuilder($this->getStoreClient());
    }

    /**
     * @return \Spryker\Client\SearchHttp\Mapper\ResultProductMapperInterface
     */
    public function createResultProductMapper(): ResultProductMapperInterface
    {
        return new ResultProductMapper();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Builder\FacetConfigBuilderInterface
     */
    public function createFacetConfigBuilder(): FacetConfigBuilderInterface
    {
        return new FacetConfigBuilder();
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SearchHttpToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCustomerClientInterface
     */
    public function getCustomerClient(): SearchHttpToCustomerClientInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): SearchHttpToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
