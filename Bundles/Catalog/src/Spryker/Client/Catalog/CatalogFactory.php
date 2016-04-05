<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\KeyBuilder\ProductResourceKeyBuilder;
use Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilder;
use Spryker\Client\Catalog\Model\Builder\QueryBuilder;
use Spryker\Client\Catalog\Model\Builder\NestedQueryBuilder;
use Spryker\Client\Catalog\Model\Catalog as ModelCatalog;
use Spryker\Client\Catalog\Model\Extractor\FacetExtractor;
use Spryker\Client\Catalog\Model\Extractor\RangeExtractor;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Catalog\Model\Query\CategorySearchQuery;
use Spryker\Client\Catalog\Model\Query\Decorator\FacetAggregatedQuery;
use Spryker\Client\Catalog\Model\Query\Decorator\FacetFilteredQuery;
use Spryker\Client\Catalog\Model\Query\Decorator\PaginatedQuery;
use Spryker\Client\Catalog\Model\Query\Decorator\SortedQuery;
use Spryker\Client\Catalog\Model\Query\FulltextSearchQuery;
use Spryker\Client\Catalog\Model\ResultFormatter\CatalogSearchResultFormatter;
use Spryker\Client\Catalog\Model\ResultFormatter\Decorator\FacetResultFormatter;
use Spryker\Client\Catalog\Model\ResultFormatter\Decorator\PaginatedResultFormatter;
use Spryker\Client\Catalog\Model\ResultFormatter\Decorator\SortedResultFormatter;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter;
use Spryker\Shared\Kernel\Store;

class CatalogFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @param int $idCategory
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function createCategorySearchQuery($idCategory, array $parameters)
    {
        $facetConfig = $this->createFacetConfig();

        $searchQuery = new CategorySearchQuery($idCategory);
        $searchQuery = $this->createSortedQuery($searchQuery, $facetConfig, $parameters);
        $searchQuery = $this->createFacetAggregatedQuery($searchQuery, $facetConfig);
        $searchQuery = $this->createFacetFilteredQuery($searchQuery, $facetConfig, $parameters);
        $searchQuery = $this->createPaginatedQuery($searchQuery, $parameters);

        return $searchQuery;
    }

    /**
     * @param string $searchString
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function createFulltextSearchQuery($searchString, array $parameters)
    {
        $facetConfig = $this->createFacetConfig();

        $searchQuery = new FulltextSearchQuery($searchString);
        $searchQuery = $this->createSortedQuery($searchQuery, $facetConfig, $parameters);
        $searchQuery = $this->createFacetAggregatedQuery($searchQuery, $facetConfig);
        $searchQuery = $this->createFacetFilteredQuery($searchQuery, $facetConfig, $parameters);
        $searchQuery = $this->createPaginatedQuery($searchQuery, $parameters);

        return $searchQuery;
    }

    /**
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface
     */
    public function createCatalogSearchResultFormatter(array $parameters)
    {
        $facetConfig = $this->createFacetConfig();

        $resultFormatter = new CatalogSearchResultFormatter($this->createCatalogModel());
        $resultFormatter = $this->createFacetResultFormatter($resultFormatter, $facetConfig, $parameters);
        $resultFormatter = $this->createPaginatedResultFormatter($resultFormatter, $parameters);
        $resultFormatter = $this->createSortedResultFormatter($resultFormatter, $facetConfig, $parameters);

        return $resultFormatter;
    }

    /**
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig()
    {
        return new FacetConfig();
    }

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    protected function createSortedQuery(QueryInterface $searchQuery, FacetConfig $facetConfig, array $parameters)
    {
        return new SortedQuery($searchQuery, $facetConfig, $parameters);
    }

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    protected function createFacetAggregatedQuery(QueryInterface $searchQuery, FacetConfig $facetConfig)
    {
        return new FacetAggregatedQuery($searchQuery, $this->createFacetAggregationBuilder(), $facetConfig);
    }

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    protected function createFacetFilteredQuery(QueryInterface $searchQuery, FacetConfig $facetConfig, array $parameters)
    {
        return new FacetFilteredQuery($searchQuery, $facetConfig, $this->createNestedFilterBuilder(), $parameters);
    }

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    protected function createPaginatedQuery(QueryInterface $searchQuery, array $parameters)
    {
        return new PaginatedQuery($searchQuery, $parameters);
    }

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter $resultFormatter
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter
     */
    protected function createFacetResultFormatter(AbstractElasticsearchResultFormatter $resultFormatter, FacetConfig $facetConfig, array $parameters)
    {
        return new FacetResultFormatter($resultFormatter, $facetConfig, $this->createFacetExtractor(), $this->createRangeExtractor(), $parameters);
    }

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter $resultFormatter
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter
     */
    protected function createPaginatedResultFormatter(AbstractElasticsearchResultFormatter $resultFormatter, array $parameters)
    {
        return new PaginatedResultFormatter($resultFormatter, $parameters);
    }

    /**
     * @param \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter $resultFormatter
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param array $parameters
     *
     * @return \Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter
     */
    protected function createSortedResultFormatter(AbstractElasticsearchResultFormatter $resultFormatter, FacetConfig $facetConfig, array $parameters)
    {
        return new SortedResultFormatter($resultFormatter, $facetConfig, $parameters);
    }

    /**
     * @return \Spryker\Client\Catalog\Model\Catalog
     */
    protected function createCatalogModel()
    {
        return new ModelCatalog(
            $this->createProductKeyBuilder(),
            $this->getKvStorage(),
            Store::getInstance()->getCurrentLocale()
        );
    }

    /**
     * @return mixed
     */
    protected function getKvStorage()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::KVSTORAGE);
    }

    /**
     * @return \Elastica\Index
     */
    protected function getSearchIndex()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::INDEX);
    }

    /**
     * @return Model\Builder\FacetAggregationBuilder
     */
    protected function createFacetAggregationBuilder()
    {
        return new FacetAggregationBuilder();
    }

    /**
     * @return Model\Extractor\FacetExtractor
     */
    protected function createFacetExtractor()
    {
        return new FacetExtractor();
    }

    /**
     * @return Model\Extractor\RangeExtractor
     */
    protected function createRangeExtractor()
    {
        return new RangeExtractor();
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Catalog\Model\Builder\NestedQueryBuilder
     */
    protected function createNestedFilterBuilder()
    {
        return new NestedQueryBuilder(
            $this->createFilterBuilder()
        );
    }

    /**
     * @return \Spryker\Client\Catalog\Model\Builder\QueryBuilder
     */
    protected function createFilterBuilder()
    {
        return new QueryBuilder();
    }

}
