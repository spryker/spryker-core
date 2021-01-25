<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Pagination;

use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

interface PaginationExpanderInterface
{
    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function addPaginationToQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface;
}
