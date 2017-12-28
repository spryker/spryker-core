<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew\Dependency\Client;

use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

interface ProductNewToSearchClientInterface
{
    /**
     * @param QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []);

    /**
     * @param QueryInterface $searchQuery
     * @param array $searchQueryExpanders
     * @param array $requestParameters
     *
     * @return QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = []);
}
