<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Communication\Plugin\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SearchElasticsearchGui\Communication\SearchElasticsearchGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SearchElasticsearchGui\Business\SearchElasticsearchGuiFacadeInterface getFacade()
 */
class DocumentListQuery extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected $searchContextTransfer;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Elastica\Query\MatchAll|\Elastica\Query
     */
    public function getSearchQuery()
    {
        $baseQuery = new Query();
        $searchString = $this->getSearchStringFromSearchContext();

        if (!empty($searchString)) {
            $query = $this->createFullTextSearchQuery($searchString);
        } else {
            $query = new MatchAll();
        }

        $baseQuery->setQuery($query);

        $this->setQueryLimit($baseQuery);
        $this->setQueryOffset($baseQuery);

        $baseQuery->setExplain(true);

        return $baseQuery;
    }

    /**
     * {@inheritDoc}
     * - Defines a context for document list search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     * - Sets a context for document list search.
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
     * @return \Elastica\Query\BoolQuery
     */
    protected function createFullTextSearchQuery(string $searchString): BoolQuery
    {
        $fields = ['_all'];

        $multiMatch = (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_CROSS_FIELDS);

        $boolQuery = (new BoolQuery())
            ->addMust($multiMatch);

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return void
     */
    protected function setQueryLimit(Query $baseQuery): void
    {
        $limit = $this->getLimitFromSearchContext();

        if ($limit) {
            $baseQuery->setSize($limit);
        }
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return void
     */
    protected function setQueryOffset(Query $baseQuery): void
    {
        $offset = $this->getOffsetFromSearchContext();

        if ($offset) {
            $baseQuery->setFrom($offset);
        }
    }

    /**
     * @return bool
     */
    protected function hasSearchContext(): bool
    {
        return (bool)$this->searchContextTransfer;
    }

    /**
     * @return string
     */
    protected function getSearchStringFromSearchContext(): string
    {
        if (!$this->searchContextTransfer) {
            return '';
        }

        return $this->searchContextTransfer
            ->requireElasticsearchContext()
            ->getElasticsearchContext()
            ->requireSearchString()
            ->getSearchString();
    }

    /**
     * @return int
     */
    protected function getLimitFromSearchContext(): int
    {
        if (!$this->searchContextTransfer) {
            return 0;
        }

        return $this->searchContextTransfer
            ->requireElasticsearchContext()
            ->getElasticsearchContext()
            ->requireLimit()
            ->getLimit();
    }

    /**
     * @return int
     */
    protected function getOffsetFromSearchContext(): int
    {
        if (!$this->searchContextTransfer) {
            return 0;
        }

        return $this->searchContextTransfer
            ->requireElasticsearchContext()
            ->getElasticsearchContext()
            ->requireOffset()
            ->getOffset();
    }
}
