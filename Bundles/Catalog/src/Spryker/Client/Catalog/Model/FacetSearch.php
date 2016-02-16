<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog\Model;

use Elastica\Filter\Term;
use Elastica\Index;
use Elastica\Query;
use Elastica\Query\Filtered;
use Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilderInterface;
use Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class FacetSearch extends AbstractSearch
{

    /**
     * @var \Spryker\Client\Catalog\Model\Builder\NestedFilterBuilderInterface
     */
    protected $filterBuilder;

    /**
     * @var array
     */
    protected $category;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param \Elastica\Index $searchIndex
     * @param \Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilderInterface $facetAggregation
     * @param \Spryker\Client\Catalog\Model\FacetFilterHandlerInterface $facetFilterHandler
     * @param \Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface $facetExtractor
     * @param \Spryker\Client\Catalog\Model\Extractor\AggregationExtractorInterface $rangeExtractor
     * @param \Spryker\Client\Catalog\Model\CatalogInterface $catalogModel
     * @param array $category
     */
    public function __construct(
        Request $request,
        FacetConfig $facetConfig,
        Index $searchIndex,
        FacetAggregationBuilderInterface $facetAggregation,
        FacetFilterHandlerInterface $facetFilterHandler,
        AggregationExtractorInterface $facetExtractor,
        AggregationExtractorInterface $rangeExtractor,
        CatalogInterface $catalogModel,
        array $category
    ) {
        parent::__construct(
            $request,
            $facetConfig,
            $searchIndex,
            $facetAggregation,
            $facetFilterHandler,
            $facetExtractor,
            $rangeExtractor,
            $catalogModel
        );
        $this->category = $category;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Elastica\Query
     */
    protected function createSearchQuery(Request $request)
    {
        $searchQuery = new Query();
        $this->addCategoryFilterToQuery($searchQuery);
        $this->addSortingToQuery($searchQuery);
        $this->addFacetAggregationToQuery($searchQuery);
        $this->addFacetFiltersToQuery($searchQuery, $request);
        $this->addPaginationToQuery($searchQuery);

        $searchQuery->setSource(['search-result-data']);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addCategoryFilterToQuery(Query $query)
    {
        $query->setQuery(
            (new Filtered())
                ->setFilter(new Term([
                    'category.all-parents' => (int)$this->category['node_id'],
                ]))
        );
    }

}
