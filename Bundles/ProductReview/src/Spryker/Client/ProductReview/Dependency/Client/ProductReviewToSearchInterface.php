<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Dependency\Client;

use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

interface ProductReviewToSearchInterface
{
    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[] $searchQueryExpanders
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = []);

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []);
}
