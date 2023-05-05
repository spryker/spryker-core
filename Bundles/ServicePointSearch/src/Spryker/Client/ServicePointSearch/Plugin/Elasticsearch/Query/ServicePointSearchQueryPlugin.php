<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Elastica\Query\MatchQuery;
use Elastica\Query\MultiMatch;
use Elastica\Suggest;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Shared\ServicePointSearch\ServicePointSearchConfig;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchConfig getConfig()
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchFactory getFactory()
 */
class ServicePointSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface, SearchStringSetterInterface, SearchStringGetterInterface
{
    /**
     * @uses \Spryker\Shared\ServicePointSearch\ServicePointSearchConfig::SERVICE_POINT_RESOURCE_NAME
     *
     * @var string
     */
    protected const SOURCE_IDENTIFIER = 'service_point';

    /**
     * @var \Elastica\Query
     */
    protected Query $query;

    /**
     * @var string|null
     */
    protected ?string $searchString = null;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer|null
     */
    protected ?SearchContextTransfer $searchContextTransfer = null;

    public function __construct()
    {
        $this->query = $this->createSearchQuery();
    }

    /**
     * {@inheritDoc}
     * - Returns query object for Service Point search.
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
     * {@inheritDoc}
     * - Defines context for Service Point search.
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
     * - Sets context for Service Point search.
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
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSearchString(): ?string
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
        $query = $query->setSource([ServicePointIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $baseQuery): Query
    {
        $matchQuery = $this->searchString ? $this->createFulltextSearchQuery($this->searchString) : new MatchAll();
        $baseQuery->setQuery($this->createBoolQuery($matchQuery));

        /** @var \Elastica\Query\BoolQuery $boolQuery */
        $boolQuery = $baseQuery->getQuery();

        $this->setTypeFilter($boolQuery);
        $this->setSuggestion($baseQuery);

        return $baseQuery;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return void
     */
    protected function setSuggestion(Query $baseQuery): void
    {
        $suggest = (new Suggest())
            ->setGlobalText((string)$this->getSearchString());

        $baseQuery->setSuggest($suggest);
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addTypeToQuery(Query $baseQuery): Query
    {
        $boolQuery = $this->setTypeFilter(new BoolQuery());

        return $baseQuery->setQuery($boolQuery);
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\MultiMatch
     */
    protected function createFulltextSearchQuery(string $searchString): MultiMatch
    {
        $fields = [
            ServicePointIndexMap::FULL_TEXT,
            ServicePointIndexMap::FULL_TEXT_BOOSTED . '^' . $this->getFullTextBoostedBoostingValue(),
        ];

        return $this->createMultiMatchQuery($fields, $searchString);
    }

    /**
     * @param array<string> $fields
     * @param string $searchString
     *
     * @return \Elastica\Query\MultiMatch
     */
    protected function createMultiMatchQuery(array $fields, string $searchString): MultiMatch
    {
        return (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_PHRASE_PREFIX);
    }

    /**
     * @param \Elastica\Query\AbstractQuery $matchQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createBoolQuery(AbstractQuery $matchQuery): BoolQuery
    {
        return (new BoolQuery())->addMust($matchQuery);
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function setTypeFilter(BoolQuery $boolQuery): BoolQuery
    {
        $matchQuery = new MatchQuery();

        $typeFilter = $matchQuery->setField(
            ServicePointIndexMap::TYPE,
            ServicePointSearchConfig::SERVICE_POINT_RESOURCE_NAME,
        );

        return $boolQuery->addMust($typeFilter);
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
        return $this->getFactory()
            ->getServicePointSearchConfig()
            ->getElasticsearchFullTextBoostedBoostingValue();
    }
}
