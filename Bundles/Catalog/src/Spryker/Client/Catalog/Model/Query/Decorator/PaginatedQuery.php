<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query\Decorator;

use Elastica\Query;
use Spryker\Client\Search\Model\Query\Decorator\AbstractQueryDecorator;
use Spryker\Client\Search\Model\Query\QueryInterface;

class PaginatedQuery extends AbstractQueryDecorator
{

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param array $parameters
     */
    public function __construct(QueryInterface $searchQuery, array $parameters)
    {
        parent::__construct($searchQuery);

        $this->parameters = $parameters;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->addPaginationToQuery($this->searchQuery->getSearchQuery());
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addPaginationToQuery(Query $query)
    {
        $currentPage = $this->getCurrentPage();
        $itemsPerPage = $this->getItemsPerPage();

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);

        return $query;
    }

    /**
     * @return int
     * TODO: add constants
     * TODO: move these methods outside somehow
     */
    protected function getCurrentPage()
    {
        return isset($this->parameters['page']) ? max((int)$this->parameters['page'], 1) : 1;
    }

    /**
     * @return int
     */
    protected function getItemsPerPage()
    {
        return isset($this->parameters['ipp']) ? max((int)$this->parameters['ipp'], 10) : 10;
    }

}
