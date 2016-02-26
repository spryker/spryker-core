<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Query\Decorator;

use Spryker\Client\Search\Model\Query\QueryInterface;

abstract class AbstractQueryDecorator implements QueryInterface
{

    /**
     * @var \Spryker\Client\Search\Model\Query\QueryInterface
     */
    protected $searchQuery;

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     */
    public function __construct(QueryInterface $searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

}
