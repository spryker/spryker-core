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
use Spryker\Client\SearchElasticsearch\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\SearchElasticsearch\Plugin\Config\PaginationConfigBuilder;
use Spryker\Client\SearchElasticsearch\Plugin\Config\SearchConfig;
use Spryker\Client\SearchElasticsearch\Plugin\Config\SortConfigBuilder;
use Spryker\Client\SearchElasticsearch\Plugin\Query\SearchKeysQuery;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilder;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface;
use Spryker\Client\SearchElasticsearch\Search\Search;
use Spryker\Client\SearchElasticsearch\Search\SearchInterface;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilder;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderInterface;
use Spryker\Client\SearchExtension\Plugin\Elasticsearch\Query\SearchStringQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\SearchElasticsearch\ElasticsearchClient\ElasticsearchClientFactory;
use Spryker\Shared\SearchElasticsearch\ElasticsearchClient\ElasticsearchClientFactoryInterface;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolver;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchFactory extends AbstractFactory
{
    /**
     * @var \Elastica\Client
     */
    protected static $client;

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigInterface
     */
    protected static $searchConfigInstance;

    /**
     * @return \Spryker\Client\SearchElasticsearch\Search\SearchInterface
     */
    public function createSearch(): SearchInterface
    {
        return new Search(
            $this->getElasticsearchClient(),
            $this->createIndexNameResolver()
        );
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    public function createIndexNameResolver(): IndexNameResolverInterface
    {
        return new IndexNameResolver(
            $this->getConfig()->getIndexNameMap()
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
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function createFacetConfigBuilder(): FacetConfigBuilderInterface
    {
        return new FacetConfigBuilder();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function createSortConfigBuilder(): SortConfigBuilderInterface
    {
        return new SortConfigBuilder();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function createPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        return new PaginationConfigBuilder();
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

    protected function createPageIndexMap()
    {
        return new PageIndexMap();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationFactoryInterface
     */
    public function createFacetAggregationFactory(): FacetAggregationFactoryInterface
    {
        return new FacetAggregationFactory(
            $this->createPageIndexMap(),
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
    public function getElasticsearchClient(): Client
    {
        return $this->createElasticsearchClientFactory()->createClient(
            $this->getConfig()->getClientConfig()
        );
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\ElasticsearchClient\ElasticsearchClientFactoryInterface
     */
    public function createElasticsearchClientFactory(): ElasticsearchClientFactoryInterface
    {
        return new ElasticsearchClientFactory();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigInterface
     */
    public function getSearchConfig(): SearchConfigInterface
    {
        if (static::$searchConfigInstance === null) {
            static::$searchConfigInstance = $this->createSearchConfig();
        }

        return static::$searchConfigInstance;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigInterface
     */
    public function createSearchConfig(): SearchConfigInterface
    {
        return new SearchConfig();
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
        return new AggregationExtractorFactory();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    public function getSearchConfigExpanderPlugins()
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SEARCH_CONFIG_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderInterface
     */
    public function getSearchConfigBuilder(): SearchConfigBuilderInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SEARCH_CONFIG_BUILDER);
    }
}
