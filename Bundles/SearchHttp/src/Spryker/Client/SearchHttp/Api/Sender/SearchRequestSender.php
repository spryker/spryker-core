<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Sender;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface;
use Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

class SearchRequestSender implements RequestSenderInterface
{
    use LoggerTrait;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected ClientInterface $httpClient;

    /**
     * @var \Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface
     */
    protected SearchHeaderBuilderInterface $headerBuilder;

    /**
     * @var \Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface
     */
    protected SearchQueryBuilderInterface $queryBuilder;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface $headerBuilder
     * @param \Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface $queryBuilder
     */
    public function __construct(
        ClientInterface $httpClient,
        SearchHeaderBuilderInterface $headerBuilder,
        SearchQueryBuilderInterface $queryBuilder
    ) {
        $this->httpClient = $httpClient;
        $this->headerBuilder = $headerBuilder;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(QueryInterface $searchQuery, SearchHttpConfigTransfer $searchHttpConfigTransfer): ResponseInterface
    {
        $httpRequest = new Request(
            SearchHttpConfig::SEARCH_HTTP_METHOD,
            $searchHttpConfigTransfer->getUrlOrFail(),
            $this->headerBuilder->build($searchQuery),
        );

        return $this->httpClient->send(
            $httpRequest,
            [
                'query' => $this->queryBuilder->build($searchQuery),
            ],
        );
    }
}
