<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Search;

use Elastica\Query;
use Elastica\Response;
use Elastica\Result;
use Elastica\ResultSet;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchClient getClient()
 */
class ElasticsearchSearchPlugin extends AbstractPlugin implements SearchPluginInterface
{
    /**
     * Currently, this Plugin accepts all queries as we only support Elasticsearch for now.
     *
     * {@inheritDoc}
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return bool
     */
    public function accept(QueryInterface $searchQuery): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        // TODO return real result
        return new ResultSet(new Response('foo', 200), new Query([]), [new Result([])]);
//        return $this->getClient()->doSearch($searchQuery, $resultFormatters, $requestParameters);
    }
}
