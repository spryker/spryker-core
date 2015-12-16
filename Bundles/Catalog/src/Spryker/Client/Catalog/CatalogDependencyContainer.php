<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog;

use Spryker\Shared\Kernel\Store;
use Spryker\Client\Catalog\KeyBuilder\ProductResourceKeyBuilder;
use Spryker\Client\Catalog\Model\Extractor\RangeExtractor;
use Spryker\Client\Catalog\Model\Extractor\FacetExtractor;
use Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilder;
use Spryker\Client\Catalog\Model\Builder\FilterBuilder;
use Spryker\Client\Catalog\Model\Builder\NestedFilterBuilder;
use Spryker\Client\Catalog\Model\FacetFilterHandler;
use Spryker\Client\Catalog\Model\FulltextSearch;
use Spryker\Client\Catalog\Model\FacetSearch;
use Spryker\Client\Catalog\Model\Catalog as ModelCatalog;
use Elastica\Index;
use Generated\Client\Ide\FactoryAutoCompletion\Catalog;
use Spryker\Client\Catalog\CatalogDependencyProvider;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class CatalogDependencyContainer extends AbstractFactory
{

    /**
     * @var Catalog
     */
    protected $factory;

    /**
     * @return Model\Catalog
     */
    public function createCatalogModel()
    {
        return new ModelCatalog(
            $this->getProductKeyBuilder(),
            $this->createStorage(),
            Store::getInstance()->getCurrentLocale()
        );
    }

    /**
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function createStorage()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::KVSTORAGE);
    }

    /**
     * @param Request $request
     * @param array $category
     *
     * @return Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category)
    {
        $facetConfig = $this->createFacetConfig();

        return new FacetSearch(
            $request,
            $facetConfig,
            $this->getSearchIndex(),
            $this->getFacetAggregationBuilder(),
            $this->createFacetFilterHandler($facetConfig),
            $this->getFacetExtractor(),
            $this->getRangeExtractor(),
            $this->createCatalogModel(),
            $category
        );
    }

    /**
     * @param Request $request
     *
     * @return Model\FulltextSearch
     */
    public function createFulltextSearch(Request $request)
    {
        $facetConfig = $this->createFacetConfig();

        return new FulltextSearch(
            $request,
            $facetConfig,
            $this->getSearchIndex(),
            $this->getFacetAggregationBuilder(),
            $this->createFacetFilterHandler($facetConfig),
            $this->getFacetExtractor(),
            $this->getRangeExtractor(),
            $this->createCatalogModel()
        );
    }

    /**
     * @return FacetConfig
     */
    public function createFacetConfig()
    {
        return new FacetConfig();
    }

    /**
     * @param FacetConfig $facetConfig
     *
     * @return Model\FacetFilterHandler
     */
    protected function createFacetFilterHandler(FacetConfig $facetConfig)
    {
        return new FacetFilterHandler(
            $this->createNestedFilterBuilder(),
            $facetConfig
        );
    }

    /**
     * @return Index
     */
    protected function getSearchIndex()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::INDEX);
    }

    /**
     * @return Model\Builder\FacetAggregationBuilder
     */
    protected function getFacetAggregationBuilder()
    {
        return new FacetAggregationBuilder();
    }

    /**
     * @return Model\Extractor\FacetExtractor
     */
    protected function getFacetExtractor()
    {
        return new FacetExtractor();
    }

    /**
     * @return Model\Extractor\RangeExtractor
     */
    protected function getRangeExtractor()
    {
        return new RangeExtractor();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function getProductKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

    /**
     * @return NestedFilterBuilder
     */
    protected function createNestedFilterBuilder()
    {
        return new NestedFilterBuilder(
            $this->createFilterBuilder()
        );
    }

    /**
     * @return FilterBuilder
     */
    protected function createFilterBuilder()
    {
        return new FilterBuilder();
    }

}
