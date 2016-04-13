<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Search\Model\Query\QueryInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SearchClient extends AbstractClient implements SearchClientInterface
{

    /**
     * @api
     *
     * @deprecated This method will be removed, because it exposes a third party vendor.
     *
     * @return \Elastica\Index
     */
    public function getIndexClient()
    {
        // TODO: remove method usages
        trigger_error('This method will be removed.', E_USER_DEPRECATED);
        return $this->getFactory()->createIndexClient();
    }

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters, array $requestParameters = [])
    {
        return $this
            ->getFactory()
            ->createElasticsearchSearchHandler()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Plugin\QueryExpanderPluginInterface[] $searchQueryExpanders
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = [])
    {
        foreach ($searchQueryExpanders as $searchQueryExpander) {
            $searchQuery = $searchQueryExpander->expandQuery($searchQuery, $requestParameters);
        }

        return $searchQuery;
    }

    /**
     * @api
     *
     * @return \Spryker\Client\Search\Plugin\Config\SearchConfigInterface
     */
    public function getSearchConfig()
    {
        return $this->getFactory()->getSearchConfig();
    }

}
