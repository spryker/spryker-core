<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Formatter;

use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface;
use Spryker\Client\SearchHttp\Exception\SearchResponseException;

class SearchResponseFormatter implements SearchResponseFormatterInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface
     */
    protected SearchHttpResponseTransferMapperInterface $httpResponseTransferMapper;

    /**
     * @param \Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface $httpResponseTransferMapper
     */
    public function __construct(SearchHttpResponseTransferMapperInterface $httpResponseTransferMapper)
    {
        $this->httpResponseTransferMapper = $httpResponseTransferMapper;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @throws \Spryker\Client\SearchHttp\Exception\SearchResponseException
     *
     * @return array<string, mixed>
     */
    public function format(
        ResponseInterface $response,
        array $resultFormatters = [],
        array $requestParameters = []
    ): array {
        $responseData = json_decode($response->getBody()->getContents(), true);

        if ($responseData === null) {
            throw new SearchResponseException('Wrong response format from Search API. Not a JSON or corrupted JSON.');
        }

        if (!isset($responseData[0]) || !is_array($responseData[0])) {
            throw new SearchResponseException('Response data from Search API is empty or invalid');
        }

        if (!$resultFormatters) {
            return $responseData[0];
        }

        $searchHttpResponseTransfer = $this->httpResponseTransferMapper->mapResponseDataToSearchHttpResponseTransfer(
            new SearchHttpResponseTransfer(),
            $responseData[0],
        );

        $formattedResults = [];

        foreach ($resultFormatters as $resultFormatter) {
            $formattedResults[$resultFormatter->getName()] = $resultFormatter->formatResult($searchHttpResponseTransfer, $requestParameters);
        }

        return $formattedResults;
    }
}
