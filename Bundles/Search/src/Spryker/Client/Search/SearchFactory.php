<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorFactory;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationFactory;
use Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryFactory;
use Spryker\Client\Search\Model\Handler\ElasticsearchSearchHandler;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Client\Search\Provider\IndexClientProvider;

class SearchFactory extends AbstractFactory
{

    /**
     * @var \Spryker\Client\Search\Plugin\Config\SearchConfigInterface
     */
    protected static $searchConfigInstance;

    /**
     * @return \Spryker\Client\Search\Plugin\Config\SearchConfigInterface
     */
    public function getSearchConfig()
    {
        if (static::$searchConfigInstance === null) {
            static::$searchConfigInstance = $this->createSearchConfig();
        }

        return static::$searchConfigInstance;
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\SearchConfigInterface
     */
    public function createSearchConfig()
    {
        return new SearchConfig();
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\SearchConfigBuilderInterface
     */
    public function getSearchConfigBuilder()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::SEARCH_CONFIG_BUILDER);
    }

    /**
     * @return \Spryker\Client\ZedRequest\Client\ZedClient
     * TODO: remove
     */
    public function createIndexClient()
    {
        return $this->createProviderIndexClientProvider()->getClient();
    }

    /**
     * @return \Spryker\Client\Search\Provider\IndexClientProvider
     */
    protected function createProviderIndexClientProvider()
    {
        return new IndexClientProvider();
    }

    /**
     * @return \Spryker\Client\Search\Model\Handler\SearchHandlerInterface
     */
    public function createElasticsearchSearchHandler()
    {
        return new ElasticsearchSearchHandler(
            $this->createProviderIndexClientProvider()->getClient()
        );
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationFactoryInterface
     */
    public function createFacetAggregationFactory()
    {
        return new FacetAggregationFactory();
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface
     */
    public function createFacetConfigBuilder()
    {
        return new FacetConfigBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\SortConfigBuilderInterface
     */
    public function createSortConfigBuilder()
    {
        return new SortConfigBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Plugin\Config\PaginationConfigBuilderInterface
     */
    public function createPaginationConfigBuilder()
    {
        return new PaginationConfigBuilder();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryFactoryInterface
     */
    public function createNestedQueryFactory()
    {
        return new NestedQueryFactory();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorFactoryInterface
     */
    public function createAggregationExtractorFactory()
    {
        return new AggregationExtractorFactory();
    }

}
