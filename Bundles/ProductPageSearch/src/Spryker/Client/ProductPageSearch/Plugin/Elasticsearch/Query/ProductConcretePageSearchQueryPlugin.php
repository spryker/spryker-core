<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Shared\Search\SearchConstants;

class ProductConcretePageSearchQueryPlugin extends AbstractPlugin implements QueryInterface
{
    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var string|null
     */
    protected $searchString;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer|null
     */
    protected $locale;

    /**
     * @var \Generated\Shared\Transfer\FilterTransfer|null
     */
    protected $filter;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     */
    public function __construct(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer)
    {
        $this->searchString = $productConcreteCriteriaFilterTransfer->getSearchString();
        $this->locale = $productConcreteCriteriaFilterTransfer->getLocale();
        $this->filter = $productConcreteCriteriaFilterTransfer->getFilter();

        $this->query = $this->createSearchQuery();
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery(): Query
    {
        $query = new Query();

        $query = $this->addFulltextSearchToQuery($query);

        $this->setSource($query)
            ->setLimit($query)
            ->setOffset($query);

        return $query;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $baseQuery): Query
    {
        $matchQuery = $this->createFulltextSearchQuery();
        $boolQuery = $this->createBoolQuery($matchQuery);
        $baseQuery->setQuery($boolQuery);

        return $baseQuery;
    }

    /**
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createFulltextSearchQuery(): AbstractQuery
    {
        if ($this->searchString === null || !strlen($this->searchString) || !$this->filter || !$this->filter->getSearchFields()) {
            return new MatchAll();
        }

        $fields = $this->getSearchFields();

        $matchQuery = (new MultiMatch())
            ->setFields($fields)
            ->setQuery($this->searchString)
            ->setType(MultiMatch::TYPE_PHRASE_PREFIX);

        return $matchQuery;
    }

    /**
     * @param \Elastica\Query\AbstractQuery $matchQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createBoolQuery(AbstractQuery $matchQuery): AbstractQuery
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust($matchQuery);
        $this->setTypeFilter($boolQuery);
        $this->setLocaleFilter($boolQuery);

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return void
     */
    protected function setTypeFilter(BoolQuery $boolQuery): void
    {
        $typeFilter = new Match();
        $typeFilter->setField(PageIndexMap::TYPE, ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME);
        $boolQuery->addMust($typeFilter);
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return void
     */
    protected function setLocaleFilter(BoolQuery $boolQuery): void
    {
        if ($this->locale && $this->locale->getLocaleName()) {
            $typeFilter = new Match();
            $typeFilter->setField(PageIndexMap::LOCALE, $this->locale->getLocaleName());
            $boolQuery->addMust($typeFilter);
        }
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return $this
     */
    protected function setLimit(Query $query): QueryInterface
    {
        if ($this->filter && $this->filter->getLimit()) {
            $query->setSize($this->filter->getLimit());
        }

        return $this;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return $this
     */
    protected function setOffset(Query $query): QueryInterface
    {
        if ($this->filter && $this->filter->getOffset()) {
            $query->setFrom($this->filter->getOffset());
        }

        return $this;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return $this
     */
    protected function setSource(Query $query): QueryInterface
    {
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $this;
    }

    /**
     * @return string[]
     */
    protected function getSearchFields(): array
    {
        if (!$this->filter || !$this->filter->getSearchFields()) {
            return [];
        }

        foreach ($this->filter->getSearchFields() as &$searchField) {
            if ($searchField === PageIndexMap::FULL_TEXT_BOOSTED) {
                $searchField = PageIndexMap::FULL_TEXT_BOOSTED . '^' . Config::get(SearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE);
            }
        }

        return $this->filter->getSearchFields();
    }
}
