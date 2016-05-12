<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

interface SearchClientInterface
{

    /**
     * @api
     *
     * @throws \Exception
     *
     * @return void
     */
    public function checkConnection();

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []);

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[] $searchQueryExpanders
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = []);

    /**
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function getSearchConfig();

    /**
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet
     */
    public function searchKeys($searchString, $limit = null, $offset = null);

}
