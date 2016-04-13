<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Search\Model\Query\QueryInterface;

interface SearchClientInterface
{

    /**
     * @api
     *
     * @return \Elastica\Index
     *
     * @deprecated This method will be removed.
     */
    public function getIndexClient();

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters, array $requestParameters = []);
    
    /**
     * @api
     *
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Plugin\QueryExpanderPluginInterface[] $searchQueryExpanders
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = []);

    /**
     * @api
     *
     * @return \Spryker\Client\Search\Plugin\Config\SearchConfigInterface
     */
    public function getSearchConfig();

}
