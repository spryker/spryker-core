<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringGetterInterface;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SortedCategoryQueryExpanderPlugin` instead.
 *
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SortedCategoryQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    protected $categoryParamName;

    /**
     * @param string $categoryParamName
     */
    public function __construct($categoryParamName)
    {
        $this->categoryParamName = $categoryParamName;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $searchConfig = $this->getFactory()->getSearchConfig();

        if ($this->isFullTextSearch($searchQuery)
            || $this->hasActiveSortParam($requestParameters, $searchConfig)
            || !$this->hasActiveCategoryFacet($requestParameters, $searchConfig)
        ) {
            return $searchQuery;
        }

        $this->addCategoryProductSorting($searchQuery, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return bool
     */
    protected function isFullTextSearch(QueryInterface $searchQuery)
    {
        if (!$searchQuery instanceof SearchStringGetterInterface) {
            return true;
        }

        $searchString = $searchQuery->getSearchString();

        return !empty($searchString);
    }

    /**
     * @param array $requestParameters
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     *
     * @return bool
     */
    protected function hasActiveSortParam(array $requestParameters, SearchConfigInterface $searchConfig)
    {
        $sortConfig = $searchConfig->getSortConfigBuilder();
        $sortParamName = $sortConfig->getActiveParamName($requestParameters);

        return !empty($sortParamName);
    }

    /**
     * @param array $requestParameters
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     *
     * @return bool
     */
    protected function hasActiveCategoryFacet(array $requestParameters, SearchConfigInterface $searchConfig)
    {
        $facetConfig = $searchConfig->getFacetConfigBuilder();
        $activeFacetParamNames = $facetConfig->getActiveParamNames($requestParameters);

        return in_array($this->categoryParamName, $activeFacetParamNames);
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addCategoryProductSorting(QueryInterface $searchQuery, array $requestParameters)
    {
        $idCategoryNode = $this->getActiveIdCategoryNode($requestParameters);
        $sortFieldName = sprintf(
            '%s.%s',
            PageIndexMap::INTEGER_SORT,
            static::buildSortFieldName($idCategoryNode)
        );

        $searchQuery
            ->getSearchQuery()
            ->addSort([
                $sortFieldName => [
                    'order' => 'ASC',
                    'mode' => 'min',
                    'unmapped_type' => 'integer',
                ],
            ]);
    }

    /**
     * @param array $requestParameters
     *
     * @return mixed
     */
    protected function getActiveIdCategoryNode(array $requestParameters)
    {
        return $requestParameters[$this->categoryParamName];
    }

    /**
     * @param int $idCategoryNode
     *
     * @return string
     */
    public static function buildSortFieldName($idCategoryNode)
    {
        return sprintf(
            '%s:%d',
            PageIndexMap::CATEGORY,
            $idCategoryNode
        );
    }
}
