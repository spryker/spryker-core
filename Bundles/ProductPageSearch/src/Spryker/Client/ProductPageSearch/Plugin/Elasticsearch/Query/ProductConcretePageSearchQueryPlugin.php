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
     * @var int
     */
    protected $limit;

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
    }

    /**
     * @param int $limit
     *
     * @return void
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
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
        if ($this->searchString === null || !strlen($this->searchString)) {
            return new MatchAll();
        }

        $fields = [
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . Config::get(SearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE),
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
}
