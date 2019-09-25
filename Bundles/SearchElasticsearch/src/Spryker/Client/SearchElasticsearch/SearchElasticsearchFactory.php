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
use Spryker\Client\SearchElasticsearch\Config\PaginationConfig;
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchElasticsearch\Plugin\Query\SearchKeysQuery;
use Spryker\Client\SearchElasticsearch\Plugin\Query\SearchStringQuery;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilder;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface;
use Spryker\Client\SearchElasticsearch\Query\QueryFactory;
use Spryker\Client\SearchElasticsearch\Query\QueryFactoryInterface;
use Spryker\Client\SearchElasticsearch\Search\Search;
use Spryker\Client\SearchElasticsearch\Search\SearchInterface;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilder;
use Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilderInterface;
use Spryker\Client\SearchExtension\Config\FacetConfigInterface;
use Spryker\Client\SearchExtension\Config\PaginationConfigInterface;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface;
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
     * @var \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected static $facetConfig;

    /**
     * @var \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
     */
    protected static $paginationConfig;

    /**
     * @var \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    protected static $sortConfig;

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
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    public function getFacetConfig(): FacetConfigInterface
    {
        if (!static::$facetConfig) {
            static::$facetConfig = new FacetConfig(
                $this->getFacetSearchConfigBuilderPlugin(),
                $this->getSearchConfigExpanderPlugins()
            );
        }

        return static::$facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    public function getSortConfig(): SortConfigInterface
    {
        if (!static::$sortConfig) {
            static::$sortConfig = new SortConfig(
                $this->getSortSearchConfigBuilderPlugin(),
                $this->getSearchConfigExpanderPlugins()
            );
        }

        return static::$sortConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
     */
    public function getPaginationConfig(): PaginationConfigInterface
    {
        if (!static::$paginationConfig) {
            static::$paginationConfig = new PaginationConfig(
                $this->getPaginationSearchConfigBuilderPlugin()
            );
        }

        return static::$paginationConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface|null
     */
    public function getFacetSearchConfigBuilderPlugin(): ?FacetConfigPluginInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SEARCH_FACET_CONFIG_BUILDER_PLUGIN);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigPluginInterface|null
     */
    public function getPaginationSearchConfigBuilderPlugin(): ?PaginationConfigPluginInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SEARCH_PAGINATION_CONFIG_BUILDER_PLUGIN);
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface|null
     */
    public function getSortSearchConfigBuilderPlugin(): ?SortConfigPluginInterface
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
