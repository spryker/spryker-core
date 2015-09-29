<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model;

use Elastica\Query;
use Elastica\Index;
use Elastica\ResultSet;
use SprykerFeature\Client\Catalog\Service\Model\Builder\FacetAggregationBuilderInterface;
use SprykerFeature\Client\Catalog\Service\Model\Extractor\AggregationExtractorInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractSearch
{

    const DEFAULT_ITEMS_PER_PAGE = 10;
    const DEFAULT_MULTI_SEARCH_OPERATOR = 'OR';
    const PAGE = 'page';
    const IPP = 'ipp';

    /**
     * @var int
     */
    protected $itemsPerPage;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var FacetConfig
     */
    protected $facetConfig;

    /**
     * @var Index
     */
    protected $searchIndex;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FacetAggregationBuilderInterface
     */
    protected $facetAggregationBuilder;

    /**
     * @var FacetFilterHandlerInterface
     */
    protected $facetFilterHandler;

    /**
     * @var AggregationExtractorInterface
     */
    protected $facetExtractor;

    /**
     * @var AggregationExtractorInterface
     */
    protected $rangeExtractor;

    /**
     * @var CatalogInterface
     */
    protected $catalogModel;

    /**
     * @var string
     */
    protected $sortParam;

    /**
     * @var string
     */
    protected $sortOrder;

    /**
     * @param Request $request
     *
     * @return Query
     */
    abstract protected function createSearchQuery(Request $request);

    /**
     * @param Request                          $request
     * @param FacetConfig                      $facetConfig
     * @param Index                            $searchIndex
     * @param FacetAggregationBuilderInterface $facetAggregation
     * @param FacetFilterHandlerInterface      $facetFilterHandler
     * @param AggregationExtractorInterface    $facetExtractor
     * @param AggregationExtractorInterface    $rangeExtractor
     * @param CatalogInterface                 $catalogModel
     */
    public function __construct(
        Request $request,
        FacetConfig $facetConfig,
        Index $searchIndex,
        FacetAggregationBuilderInterface $facetAggregation,
        FacetFilterHandlerInterface $facetFilterHandler,
        AggregationExtractorInterface $facetExtractor,
        AggregationExtractorInterface $rangeExtractor,
        CatalogInterface $catalogModel
    ) {
        $this->request = $request;
        $this->facetConfig = $facetConfig;
        $this->searchIndex = $searchIndex;
        $this->facetAggregationBuilder = $facetAggregation;
        $this->facetFilterHandler = $facetFilterHandler;
        $this->facetExtractor = $facetExtractor;
        $this->rangeExtractor = $rangeExtractor;
        $this->catalogModel = $catalogModel;

        $this->currentPage = (int) $this->request->query->get(self::PAGE, 1);
        $this->itemsPerPage = (int) $this->request->query->get(self::IPP, self::DEFAULT_ITEMS_PER_PAGE);
    }

    /**
     * @return array
     */
    public function getResult()
    {
        $searchQuery = $this->createSearchQuery($this->request);
        $resultSet = $this->searchIndex->search($searchQuery);
        $ids = $this->extractProductIdsFromResultSet($resultSet);
        $products = [];
        if ($ids) {
            $products = $this->catalogModel->getProductDataByIds($ids);
        }
        $activeParameters = iterator_to_array($this->request->query);

        return [
            'products' => $products,
            'facets' => $this->extractFacetDataFromResult($resultSet, $activeParameters),
            'numFound' => $resultSet->getTotalHits(),
            'currentPage' => $this->currentPage,
            'maxPage' => ceil($resultSet->getTotalHits() / $this->itemsPerPage),
            'currentItemsPerPage' => $this->itemsPerPage,
            'sortNames' => array_keys($this->facetConfig->getActiveSortAttributes()),
            'currentSortParam' => $this->sortParam,
            'currentSortOrder' => $this->sortOrder,
        ];
    }

    /**
     * @param ResultSet $resultSet
     *
     * @return array
     */
    public function extractProductIdsFromResultSet(ResultSet $resultSet)
    {
        $ids = [];
        foreach ($resultSet->getResults() as $result) {
            $product = $result->getSource();
            $ids[] = $product[FacetConfig::FIELD_SEARCH_RESULT_DATA]['id_abstract_product'];
        }

        return $ids;
    }

    /**
     * @param ResultSet $resultSet
     * @param array $activeParameters
     *
     * @return array
     */
    protected function extractFacetDataFromResult(ResultSet $resultSet, array $activeParameters)
    {
        $facetFields = $this->facetConfig->getFacetFields();
        $numericFacetFields = $this->facetConfig->getNumericFacetFields();

        $aggregations = $resultSet->getAggregations();
        $facets = $this->facetExtractor->extractDataFromAggregations($aggregations, $facetFields);
        $ranges = $this->rangeExtractor->extractDataFromAggregations($aggregations, $numericFacetFields);

        return $this->createFacetResult($activeParameters, $facets, $ranges);
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @param Query $query
     *
     * @return self
     */
    protected function addSortingToQuery(Query $query)
    {
        if ($this->request->query->has('sort')) {
            $this->sortParam = $this->request->query->get('sort');
            $sortParam = $this->request->query->get('sort');
            $sortField = $this->facetConfig->getSortFieldFromParam($sortParam);
            $sortOrder = 'asc';
            if ($this->request->query->has('sort_order')) {
                $this->sortOrder = $this->request->query->get('sort_order');
                $sortOrder = $this->request->query->get('sort_order');
            }
            $nestedSortField = implode('.', [$sortField, $sortParam]);
            $query->setSort(
                [
                    $nestedSortField => [
                            'order' => $sortOrder,
                            'mode' => 'min',
                        ],
                ]
            );
        }

        return $this;
    }

    /**
     * @param Query $query
     */
    protected function addFacetAggregationToQuery(Query $query)
    {
        $stringFacetField = $this->facetConfig->getStringFacetField();
        $floatFacetField = $this->facetConfig->getFloatFacetField();
        $integerFacetField = $this->facetConfig->getIntegerFacetField();

        $query->addAggregation($this->facetAggregationBuilder->createStringFacetAggregation($stringFacetField));
        $query->addAggregation($this->facetAggregationBuilder->createNumberFacetAggregation($integerFacetField));
        $query->addAggregation($this->facetAggregationBuilder->createNumberFacetAggregation($floatFacetField));
    }

    /**
     * @param Query $query
     * @param Request $request
     */
    protected function addFacetFiltersToQuery(Query $query, Request $request)
    {
        $this->facetFilterHandler->addFacetFiltersToQuery($query, $request);
    }

    /**
     * @param Query $query
     */
    protected function addPaginationToQuery(Query $query)
    {
        $query->setFrom(($this->currentPage - 1) * $this->itemsPerPage);
        $query->setSize($this->itemsPerPage);
    }

    /**
     * @param array $activeParameters
     * @param array $facets
     * @param array $ranges
     *
     * @return array
     */
    protected function createFacetResult(array $activeParameters, array $facets, array $ranges)
    {
        $preparedFacets = [];
        foreach ($this->facetConfig->getActiveFacets() as $currentFacetName => $facetConfig) {
            $paramName = $facetConfig[FacetConfig::KEY_PARAM];
            if (isset($facets[$currentFacetName])) {
                $currentFacet = [
                    'name' => $paramName,
                    'config' => $facetConfig,
                    'values' => $facets[$currentFacetName],
                ];
                if (isset($activeParameters[$paramName])) {
                    $currentFacet['activeValue'] = $activeParameters[$paramName];
                }
                if ($facetConfig[FacetConfig::KEY_TYPE] === FacetConfig::TYPE_SLIDER) {
                    $currentFacet['rangeValues'] = $ranges[$currentFacetName];
                }
                $preparedFacets[$currentFacetName] = $currentFacet;
            }
        }

        return $preparedFacets;
    }

}
