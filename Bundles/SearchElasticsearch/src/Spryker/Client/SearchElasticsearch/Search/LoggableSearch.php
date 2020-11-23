<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Search;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchLoggerInterface;

class LoggableSearch implements SearchInterface
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Search\SearchInterface
     */
    protected $search;

    /**
     * @var \Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchLoggerInterface
     */
    protected $elasticsearchLogger;

    /**
     * @param \Spryker\Client\SearchElasticsearch\Search\SearchInterface $search
     * @param \Spryker\Shared\SearchElasticsearch\Logger\ElasticsearchLoggerInterface $elasticsearchLogger
     */
    public function __construct(SearchInterface $search, ElasticsearchLoggerInterface $elasticsearchLogger)
    {
        $this->search = $search;
        $this->elasticsearchLogger = $elasticsearchLogger;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        $result = $this->search->search($searchQuery, $resultFormatters, $requestParameters);

        $this->elasticsearchLogger->log(
            [
                'query' => $searchQuery->getSearchQuery()->toArray(),
                'parameters' => $requestParameters,
            ],
            $result
        );

        return $result;
    }
}
