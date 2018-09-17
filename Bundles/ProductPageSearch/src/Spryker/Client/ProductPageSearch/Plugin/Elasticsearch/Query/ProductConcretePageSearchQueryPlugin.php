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
use Spryker\Shared\Config\Config;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Shared\Search\SearchConstants;

class ProductConcretePageSearchQueryPlugin extends AbstractPlugin implements ProductConcretePageSearchQueryPluginInterface
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
     * @var int
     */
    protected $limit;

    /**
     * @var string[]
     */
    protected $searchFields;

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return \Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query\ProductConcretePageSearchQueryPluginInterface
     */
    public function setProductConcreteCriteriaFilter(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): ProductConcretePageSearchQueryPluginInterface
    {
        $this->searchString = $productConcreteCriteriaFilterTransfer->getSearchString();
        $this->searchFields = $productConcreteCriteriaFilterTransfer->getSearchFields();
        $this->locale = $productConcreteCriteriaFilterTransfer->getLocale();
        $this->limit = $productConcreteCriteriaFilterTransfer->getLimit();

        return $this;
    }

    /**
     * @return \Elastica\Query
     */
    public function buildQuery(): Query
    {
        $this->query = new Query();
        $this->addFulltextSearchToQuery();
        $this->setQuerySource();
        $this->setQueryLimit();

        return $this->query;
    }

    /**
     * @return void
     */
    protected function addFulltextSearchToQuery(): void
    {
        $matchQuery = $this->createFulltextSearchQuery();
        $boolQuery = $this->createBoolQuery($matchQuery);
        $this->query->setQuery($boolQuery);
    }

    /**
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createFulltextSearchQuery(): AbstractQuery
    {
        $this->prepareSearchFields();

        if ($this->searchString === null || !strlen($this->searchString) || !$this->searchFields) {
            return new MatchAll();
        }

        $matchQuery = (new MultiMatch())
            ->setFields($this->searchFields)
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
        $boolQuery = $this->setTypeFilter($boolQuery);
        $boolQuery = $this->setLocaleFilter($boolQuery);

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function setTypeFilter(BoolQuery $boolQuery): BoolQuery
    {
        $typeFilter = new Match();
        $typeFilter->setField(PageIndexMap::TYPE, ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME);
        $boolQuery->addMust($typeFilter);

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function setLocaleFilter(BoolQuery $boolQuery): BoolQuery
    {
        if ($this->locale && $this->locale->getLocaleName()) {
            $typeFilter = new Match();
            $typeFilter->setField(PageIndexMap::LOCALE, $this->locale->getLocaleName());
            $boolQuery->addMust($typeFilter);
        }

        return $boolQuery;
    }

    /**
     * @return void
     */
    protected function setQueryLimit(): void
    {
        if ($this->limit) {
            $this->query->setSize($this->limit);
        }
    }

    /**
     * @return void
     */
    protected function setQuerySource(): void
    {
        $this->query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);
    }

    /**
     * @return void
     */
    protected function prepareSearchFields(): void
    {
        if (!$this->searchFields) {
            return;
        }

        foreach ($this->searchFields as &$searchField) {
            if ($searchField === PageIndexMap::FULL_TEXT_BOOSTED) {
                $searchField = PageIndexMap::FULL_TEXT_BOOSTED . '^' . Config::get(SearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE);
            }
        }
    }
}
