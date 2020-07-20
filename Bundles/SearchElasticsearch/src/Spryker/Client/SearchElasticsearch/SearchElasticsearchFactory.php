<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Elastica\Client;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilder;
use Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilderInterface;
use Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationFactory;
use Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationFactoryInterface;
use Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorFactory;
use Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorFactoryInterface;
use Spryker\Client\SearchElasticsearch\AggregationExtractor\FacetValueTransformerFactory;
use Spryker\Client\SearchElasticsearch\AggregationExtractor\FacetValueTransformerFactoryInterface;
use Spryker\Client\SearchElasticsearch\Config\FacetConfig;
use Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface;
use Spryker\Client\SearchElasticsearch\Config\PaginationConfig;
use Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface;
use Spryker\Client\SearchElasticsearch\Config\SearchConfigBuilder;
use Spryker\Client\SearchElasticsearch\Config\SearchConfigBuilderInterface;
use Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface;
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchElasticsearch\Config\SortConfigInterface;
use Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface;
use Spryker\Client\SearchElasticsearch\Index\SourceIdentifier;
use Spryker\Client\SearchElasticsearch\Index\SourceIdentifierInterface;
use Spryker\Client\SearchElasticsearch\Plugin\Query\SearchKeysQuery;
use Spryker\Client\SearchElasticsearch\Plugin\Query\SearchStringQuery;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilder;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface;
use Spryker\Client\SearchElasticsearch\Query\QueryFactory;
use Spryker\Client\SearchElasticsearch\Query\QueryFactoryInterface;
use Spryker\Client\SearchElasticsearch\Reader\DocumentReader;
use Spryker\Client\SearchElasticsearch\Reader\DocumentReaderInterface;
use Spryker\Client\SearchElasticsearch\Reader\MappingTypeAwareDocumentReader;
use Spryker\Client\SearchElasticsearch\Search\Search;
use Spryker\Client\SearchElasticsearch\Search\SearchInterface;
use Spryker\Client\SearchElasticsearch\SearchContextExpander\SearchContextExpander;
use Spryker\Client\SearchElasticsearch\SearchContextExpander\SearchContextExpanderInterface;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilder;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilderInterface;
use Spryker\Client\SearchElasticsearch\Writer\DocumentWriter;
use Spryker\Client\SearchElasticsearch\Writer\DocumentWriterInterface;
use Spryker\Client\SearchElasticsearch\Writer\MappingTypeAwareDocumentWriter;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToLocaleClientInterface;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface;
use Spryker\Shared\SearchElasticsearch\ElasticaClient\ElasticaClientFactory;
use Spryker\Shared\SearchElasticsearch\ElasticaClient\ElasticaClientFactoryInterface;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolver;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;
use Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetector;
use Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface;
use Spryker\Shared\SearchExtension\SourceInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SearchElasticsearch\Search\SearchInterface
     */
    public function createSearch(): SearchInterface
    {
        return new Search(
            $this->getElasticaClient()
        );
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    public function createIndexNameResolver(): IndexNameResolverInterface
    {
        return new IndexNameResolver(
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilderInterface
     */
    public function createAggregationBuilder(): AggregationBuilderInterface
    {
        return new AggregationBuilder();
    }

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createSearchKeysQuery(string $searchString, ?int $limit = null, ?int $offset = null): QueryInterface
    {
        return new SearchKeysQuery($searchString, $this->getConfig(), $limit, $offset);
    }

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createSearchStringQuery(string $searchString, ?int $limit = null, ?int $offset = null): QueryInterface
    {
        return new SearchStringQuery($searchString, $limit, $offset);
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface
     */
    public function createQueryBuilder(): QueryBuilderInterface
    {
        return new QueryBuilder();
    }

    /**
     * @return \Spryker\Shared\SearchExtension\SourceInterface
     */
    protected function createSource(): SourceInterface
    {
        return new PageIndexMap();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationFactoryInterface
     */
    public function createFacetAggregationFactory(): FacetAggregationFactoryInterface
    {
        return new FacetAggregationFactory(
            $this->createSource(),
            $this->createAggregationBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\FacetValueTransformerFactoryInterface
     */
    public function createFacetValueTransformerFactory(): FacetValueTransformerFactoryInterface
    {
        return new FacetValueTransformerFactory();
    }

    /**
     * @return \Elastica\Client
     */
    public function getElasticaClient(): Client
    {
        return $this->createElasticaClientFactory()->createClient(
            $this->getConfig()->getClientConfig()
        );
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\ElasticaClient\ElasticaClientFactoryInterface
     */
    public function createElasticaClientFactory(): ElasticaClientFactoryInterface
    {
        return new ElasticaClientFactory();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilderInterface
     */
    public function createSuggestBuilder(): SuggestBuilderInterface
    {
        return new SuggestBuilder();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorFactoryInterface
     */
    public function createAggregationExtractorFactory(): AggregationExtractorFactoryInterface
    {
        return new AggregationExtractorFactory($this->getMoneyClient());
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    public function getSearchConfigExpanderPlugins()
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::PLUGINS_SEARCH_CONFIG_EXPANDER);
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface
     */
    public function getStoreClient(): SearchElasticsearchToStoreClientInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToLocaleClientInterface
     */
    public function getLocaleClient(): SearchElasticsearchToLocaleClientInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Query\QueryFactoryInterface
     */
    public function createQueryFactory(): QueryFactoryInterface
    {
        return new QueryFactory(
            $this->createQueryBuilder(),
            $this->getMoneyClient()
        );
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Index\SourceIdentifier
     */
    public function createSourceIdentifierChecker(): SourceIdentifierInterface
    {
        return new SourceIdentifier($this->getConfig());
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\SearchContextExpander\SearchContextExpanderInterface
     */
    public function createSearchContextExpander(): SearchContextExpanderInterface
    {
        return new SearchContextExpander(
            $this->createIndexNameResolver()
        );
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Dependency\Client\SearchElasticsearchToMoneyClientInterface
     */
    public function getMoneyClient(): SearchElasticsearchToMoneyClientInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::CLIENT_MONEY);
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    public function getSearchConfig(): SearchConfigInterface
    {
        return $this->createSearchConfigBuilder()->build();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigBuilderInterface
     */
    public function createSearchConfigBuilder(): SearchConfigBuilderInterface
    {
        $searchConfigBuilder = new SearchConfigBuilder(
            $this->createFacetConfig(),
            $this->createSortConfig(),
            $this->createPaginationConfig()
        );
        $searchConfigBuilder->setSearchConfigBuilderPlugins(
            $this->getSearchConfigBuilderPlugins()
        );
        $searchConfigBuilder->setSearchConfigExpanderPlugins(
            $this->getSearchConfigExpanderPlugins()
        );

        return $searchConfigBuilder;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface
     */
    public function createFacetConfig(): FacetConfigInterface
    {
        return new FacetConfig();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SortConfigInterface
     */
    public function createSortConfig(): SortConfigInterface
    {
        return new SortConfig();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface
     */
    public function createPaginationConfig(): PaginationConfigInterface
    {
        return new PaginationConfig();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface[]
     */
    public function getSearchConfigBuilderPlugins(): array
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::PLUGINS_SEARCH_CONFIG_BUILDER);
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Writer\DocumentWriterInterface
     */
    public function createDocumentWriter(): DocumentWriterInterface
    {
        if ($this->createMappingTypeSupportDetector()->isMappingTypesSupported()) {
            return new MappingTypeAwareDocumentWriter($this->getElasticaClient());
        }

        return new DocumentWriter($this->getElasticaClient());
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Reader\DocumentReaderInterface
     */
    public function createDocumentReader(): DocumentReaderInterface
    {
        if ($this->createMappingTypeSupportDetector()->isMappingTypesSupported()) {
            return new MappingTypeAwareDocumentReader($this->getElasticaClient());
        }

        return new DocumentReader($this->getElasticaClient());
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface
     */
    public function createMappingTypeSupportDetector(): MappingTypeSupportDetectorInterface
    {
        return new MappingTypeSupportDetector();
    }
}
