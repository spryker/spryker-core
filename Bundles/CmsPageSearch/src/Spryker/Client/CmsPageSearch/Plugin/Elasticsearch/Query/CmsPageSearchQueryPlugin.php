<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Elastica\Suggest;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\NamedQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Search\SearchConstants;

class CmsPageSearchQueryPlugin extends AbstractPlugin implements QueryInterface, NamedQueryInterface, SearchStringSetterInterface, SearchStringGetterInterface
{
    protected const TYPE = 'cms_page';

    /**
     * @var string
     */
    protected $searchString = '';

    /**
     * @var \Elastica\Query
     */
    protected $query;

    public function __construct()
    {
        $this->query = $this->createSearchQuery();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getIndexName(): string
    {
        return 'page';
    }

    /**
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString): void
    {
        $this->searchString = $searchString;
        $this->query = $this->createSearchQuery();
    }

    /**
     * @return string
     */
    public function getSearchString(): string
    {
        return $this->searchString;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery(): Query
    {
        $query = new Query();
        $query = $this->addFulltextSearchToQuery($query);
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createFulltextSearchQuery(string $searchString): AbstractQuery
    {
        $fields = [
            PageIndexMap::FULL_TEXT,
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . Config::get(SearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE),
        ];

        $matchQuery = (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_CROSS_FIELDS);

        return $matchQuery;
    }

    /**
     * @param \Elastica\Query\AbstractQuery $matchQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createBoolQuery(AbstractQuery $matchQuery): BoolQuery
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust($matchQuery);
        $this->setTypeFilter($boolQuery);

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $baseQuery): Query
    {
        if (!empty($this->searchString)) {
            $matchQuery = $this->createFulltextSearchQuery($this->searchString);
        } else {
            $matchQuery = new MatchAll();
        }

        $baseQuery->setQuery($this->createBoolQuery($matchQuery));

        return $baseQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return void
     */
    protected function setTypeFilter(BoolQuery $boolQuery): void
    {
        $typeFilter = (new Match())
            ->setField(PageIndexMap::TYPE, static::TYPE);

        $boolQuery->addMust($typeFilter);
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return void
     */
    protected function setSuggestion(Query $baseQuery): void
    {
        $suggest = new Suggest();
        $suggest->setGlobalText($this->getSearchString());

        $baseQuery->setSuggest($suggest);
    }
}
