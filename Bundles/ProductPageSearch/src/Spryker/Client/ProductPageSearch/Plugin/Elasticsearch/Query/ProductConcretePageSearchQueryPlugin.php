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
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;

/**
 * @method \Spryker\Client\ProductPageSearch\ProductPageSearchFactory getFactory()
 */
class ProductConcretePageSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchStringSetterInterface
{
    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var string|null
     */
    protected $searchString;

    public function __construct()
    {
        $this->createQuery();
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
        $this->createQuery();
    }

    /**
     * @return \Elastica\Query
     */
    protected function createQuery(): Query
    {
        $this->query = new Query();
        $this->addFulltextSearchToQuery();
        $this->setQuerySource();

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
        if ($this->searchString === null || !strlen($this->searchString)) {
            return new MatchAll();
        }

        $fields = [
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . $this->getFullTextBoostedBoostingValue(),
        ];

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
        $boolQuery = $this->setTypeFilter($boolQuery);

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
     * @return void
     */
    protected function setQuerySource(): void
    {
        $this->query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);
    }

    /**
     * @return int
     */
    protected function getFullTextBoostedBoostingValue(): int
    {
        return $this->getFactory()
            ->getProductPageSearchConfig()
            ->getFullTextBoostedBoostingValue();
    }
}
