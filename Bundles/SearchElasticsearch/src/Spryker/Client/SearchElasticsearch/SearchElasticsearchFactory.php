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
use Spryker\Client\SearchElasticsearch\Plugin\Config\SortConfigBuilder;
use Spryker\Client\SearchElasticsearch\Plugin\Query\SearchKeysQuery;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilder;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface;
use Spryker\Client\SearchElasticsearch\Query\QueryFactory;
use Spryker\Client\SearchElasticsearch\Query\QueryFactoryInterface;
use Spryker\Client\SearchElasticsearch\Search\Search;
use Spryker\Client\SearchElasticsearch\Search\SearchInterface;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilder;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilderInterface;
use Spryker\Client\SearchExtension\Config\FacetConfigBuilderInterface;
use Spryker\Client\SearchExtension\Config\PaginationConfigBuilderInterface;
use Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationSearchConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortSearchConfigBuilderPluginInterface;
use Spryker\Client\SearchExtension\Plugin\Query\SearchStringQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
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
     * @var \Spryker\Client\SearchExtension\Config\FacetConfigBuilderInterface
     */
    protected static $facetConfigBuilder;

    /**
     * @var \Spryker\Client\SearchExtension\Config\PaginationConfigBuilderInterface
     */
    protected static $paginationConfigBuilder;

    /**
     * @var \Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface
     */
    protected static $sortConfigBuilder;

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
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder(): FacetConfigBuilderInterface
    {
        if (!static::$facetConfigBuilder) {
            static::$facetConfigBuilder = new FacetConfigBuilder(
                $this->getFacetSearchConfigBuilderPlugin(),
                $this->getSearchConfigExpanderPlugins()
            );
        }

        return static::$facetConfigBuilder;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder(): SortConfigBuilderInterface
    {
        if (!static::$sortConfigBuilder) {
            static::$sortConfigBuilder = new SortConfigBuilder(
                $this->getSortSearchConfigBuilderPlugin(),
                $this->getSearchConfigExpanderPlugins()
            );
        }

        return static::$sortConfigBuilder;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder(): PaginationConfigBuilderInterface
    {
        if (!static::$paginationConfigBuilder) {
            static::$paginationConfigBuilder = new PaginationConfigBuilder(
                $this->getPaginationSearchConfigBuilderPlugin()
            );
        }

        return static::$paginationConfigBuilder;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchConfigBuilderPluginInterface|null
     */
    public function getFacetSearchConfigBuilderPlugin(): ?FacetSearchConfigBuilderPluginInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SEARCH_FACET_CONFIG_BUILDER_PLUGIN);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationSearchConfigBuilderPluginInterface|null
     */
    public function getPaginationSearchConfigBuilderPlugin(): ?PaginationSearchConfigBuilderPluginInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SEARCH_PAGINATION_CONFIG_BUILDER_PLUGIN);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SortSearchConfigBuilderPluginInterface|null
     */
    public function getSortSearchConfigBuilderPlugin(): ?SortSearchConfigBuilderPluginInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SEARCH_SORT_CONFIG_BUILDER_PLUGIN);
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
     * @return \Generated\Shared\Search\PageIndexMap
     */
    protected function createPageIndexMap(): PageIndexMap
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
     * @return \Spryker\Client\SearchElasticsearch\Query\QueryFactoryInterface
     */
    public function createQueryFactory(): QueryFactoryInterface
    {
        return new QueryFactory(
            $this->createQueryBuilder(),
            $this->getMoneyPlugin()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::PLUGIN_MONEY);
    }
}
