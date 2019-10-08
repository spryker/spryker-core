<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\QueryExpander;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringGetterInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
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
    public function __construct(string $categoryParamName)
    {
        $this->categoryParamName = $categoryParamName;
    }

    /**
     * {@inheritDoc}
     * - Adds category sorting.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        if ($this->isFullTextSearch($searchQuery)
            || $this->hasActiveSortParam($requestParameters)
            || !$this->hasActiveCategoryFacet($requestParameters)
        ) {
            return $searchQuery;
        }

        $this->addCategoryProductSorting($searchQuery, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return bool
     */
    protected function isFullTextSearch(QueryInterface $searchQuery): bool
    {
        if (!$searchQuery instanceof SearchStringGetterInterface) {
            return true;
        }

        $searchString = $searchQuery->getSearchString();

        return !empty($searchString);
    }

    /**
     * @param array $requestParameters
     *
     * @return bool
     */
    protected function hasActiveSortParam(array $requestParameters): bool
    {
        $sortConfig = $this->getFactory()->getSearchConfig()->getSortConfig();
        $sortParamName = $sortConfig->getActiveParamName($requestParameters);

        return !empty($sortParamName);
    }

    /**
     * @param array $requestParameters
     *
     * @return bool
     */
    protected function hasActiveCategoryFacet(array $requestParameters): bool
    {
        $facetConfig = $this->getFactory()->getSearchConfig()->getFacetConfig();
        $activeFacetParamNames = $facetConfig->getActiveParamNames($requestParameters);

        return in_array($this->categoryParamName, $activeFacetParamNames, true);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addCategoryProductSorting(QueryInterface $searchQuery, array $requestParameters): void
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
    public static function buildSortFieldName(int $idCategoryNode): string
    {
        return sprintf(
            '%s:%d',
            PageIndexMap::CATEGORY,
            $idCategoryNode
        );
    }
}
