<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Delegator\SearchDelegator;
use Spryker\Client\Search\Delegator\SearchDelegatorInterface;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilder;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationFactory;
use Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorFactory;
use Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactory;
use Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilder;
use Spryker\Client\Search\Model\Elasticsearch\Query\QueryFactory;
use Spryker\Client\Search\Model\Elasticsearch\Reader\Reader;
use Spryker\Client\Search\Model\Elasticsearch\Suggest\SuggestBuilder;
use Spryker\Client\Search\Model\Elasticsearch\Writer\Writer;
use Spryker\Client\Search\Model\Handler\ElasticsearchSearchHandler;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchKeysQuery;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchStringQuery;
use Spryker\Client\Search\Provider\IndexClientProvider;
use Spryker\Client\Search\Provider\SearchClientProvider;

/**
 * @method \Spryker\Client\Search\SearchConfig getConfig()
 */
class SearchFactory extends AbstractFactory
{
    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected static $searchConfigInstance;

    /**
     * @var \Elastica\Client
     */
    protected static $searchClient;

    /**
     * @return \Spryker\Client\Search\Delegator\SearchDelegatorInterface
     */
    public function createSearchDelegator(): SearchDelegatorInterface
    {
        return new SearchDelegator(
            $this->getClientAdapterPlugins()
        );
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[]
     */
    public function getClientAdapterPlugins(): array
    {
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_ADAPTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function getSearchConfig()
    {
        if (static::$searchConfigInstance === null) {
            static::$searchConfigInstance = $this->createSearchConfig();
        }

        return static::$searchConfigInstance;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function createSearchConfig()
    {
        return new SearchConfig();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface
     */
    public function getSearchConfigBuilder()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::SEARCH_CONFIG_BUILDER);
    }

    /**
     * @return \Elastica\Client
     */
    public function getElasticsearchClient()
    {
        /** @var \Elastica\Client $client */
        $client = $this->createSearchClientProvider()->getInstance();

        return $client;
    }

    /**
     * @return \Spryker\Client\Search\Provider\SearchClientProvider
     */
    protected function createSearchClientProvider()
    {
        return new SearchClientProvider();
    }

    /**
     * @deprecated Use `\Spryker\Client\Search\SearchFactory::createSearchDelegator()` instead.
     *
     * @return \Spryker\Client\Search\Model\Handler\SearchHandlerInterface|\Spryker\Client\Search\Search\SearchInterface
     */
    public function createElasticsearchSearchHandler()
    {
        if (count($this->getClientAdapterPlugins()) > 0) {
            return $this->createSearchDelegator();
        }

        return new ElasticsearchSearchHandler(
            $this->createIndexClientProvider()
        );
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchPluginInterface[]
     */
    public function getSearchPlugins(): array
    {
        return $this->getProvidedDependency(SearchDependencyProvider::SEARCH_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Provider\IndexClientProvider
     */
    protected function createIndexClientProvider()
    {
        return new IndexClientProvider();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationFactoryInterface
     */
    public function createFacetAggregationFactory()
    {
        return new FacetAggregationFactory(
            $this->createPageIndexMap(),
            $this->createAggregationBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function createFacetConfigBuilder()
    {
        return new FacetConfigBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function createSortConfigBuilder()
    {
        return new SortConfigBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function createPaginationConfigBuilder()
    {
        return new PaginationConfigBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\QueryFactoryInterface
     */
    public function createQueryFactory()
    {
        return new QueryFactory($this->createQueryBuilder(), $this->getMoneyPlugin());
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function getMoneyPlugin()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorFactoryInterface
     */
    public function createAggregationExtractorFactory()
    {
        return new AggregationExtractorFactory();
    }

    /**
     * @return \Generated\Shared\Search\PageIndexMap
     */
    protected function createPageIndexMap()
    {
        return new PageIndexMap();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface
     */
    public function createAggregationBuilder()
    {
        return new AggregationBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Suggest\SuggestBuilderInterface
     */
    public function createSuggestBuilder()
    {
        return new SuggestBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactoryInterface
     */
    public function createFacetValueTransformerFactory()
    {
        return new FacetValueTransformerFactory();
    }

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createSearchKeysQuery($searchString, $limit = null, $offset = null)
    {
        return new SearchKeysQuery($searchString, $limit, $offset);
    }

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createSearchStringQuery($searchString, $limit = null, $offset = null)
    {
        return new SearchStringQuery($searchString, $limit, $offset);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    public function getSearchConfigExpanderPlugins()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::SEARCH_CONFIG_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Writer\WriterInterface
     */
    public function createWriter()
    {
        return new Writer(
            $this->createCachedElasticsearchClient(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType()
        );
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Reader\ReaderInterface
     */
    public function createReader()
    {
        return new Reader(
            $this->createCachedElasticsearchClient(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType()
        );
    }

    /**
     * @return \Elastica\Client
     */
    public function createCachedElasticsearchClient()
    {
        if (static::$searchClient === null) {
            static::$searchClient = $this->getElasticsearchClient();
        }

        return static::$searchClient;
    }
}
