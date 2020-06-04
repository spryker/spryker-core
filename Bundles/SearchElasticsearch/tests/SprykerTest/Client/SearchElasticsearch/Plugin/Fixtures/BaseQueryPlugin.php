<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\Fixtures;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;

class BaseQueryPlugin implements QueryInterface, SearchContextAwareQueryInterface, SearchStringSetterInterface, SearchStringGetterInterface
{
    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected $searchContextTransfer;

    /**
     * @var string
     */
    protected $searchString;

    public function __construct()
    {
        $query = new Query();
        $query->setQuery(new BoolQuery());

        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     * - Returns a query object for base search.
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
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString): void
    {
        $this->searchString = $searchString;
    }

    /**
     * @return string|null
     */
    public function getSearchString(): ?string
    {
        return $this->searchString;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated This method will be moved to `\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface`.
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->searchContextTransfer) {
            $this->searchContextTransfer = new SearchContextTransfer();
        }

        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated This method will be moved to `\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface`.
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }
}
