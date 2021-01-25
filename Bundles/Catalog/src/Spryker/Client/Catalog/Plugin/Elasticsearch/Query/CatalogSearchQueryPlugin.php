<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface, SearchStringSetterInterface, SearchStringGetterInterface
{
    protected const SOURCE_IDENTIFIER = 'page';

    /**
     * @var string
     */
    protected $searchString;

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected $searchContextTransfer;

    public function __construct()
    {
        $this->query = $this->createSearchQuery();
    }

    /**
     * {@inheritDoc}
     * - Returns query object for catalog search.
     *
     * @api
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     * - Defines a context for catalog search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->hasSearchContext()) {
            $this->setupDefaultSearchContext();
        }

        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     * - Sets a context for catalog search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
        $this->query = $this->createSearchQuery();
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery()
    {
        $query = new Query();
        $query = $this->addFulltextSearchToQuery($query);
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $baseQuery)
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
     * @param string $searchString
     *
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createFulltextSearchQuery($searchString)
    {
        $fields = [
            PageIndexMap::FULL_TEXT,
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . $this->getFullTextBoostedBoostingValue(),
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
    protected function createBoolQuery(AbstractQuery $matchQuery)
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust($matchQuery);

        return $boolQuery;
    }

    /**
     * @return void
     */
    protected function setupDefaultSearchContext(): void
    {
        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setSourceIdentifier(static::SOURCE_IDENTIFIER);

        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return bool
     */
    protected function hasSearchContext(): bool
    {
        return (bool)$this->searchContextTransfer;
    }

    /**
     * @return int
     */
    protected function getFullTextBoostedBoostingValue(): int
    {
        return $this->getFactory()->getConfig()->getElasticsearchFullTextBoostedBoostingValue();
    }
}
