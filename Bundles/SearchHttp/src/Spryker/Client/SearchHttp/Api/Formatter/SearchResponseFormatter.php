<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Formatter;

use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\GroupedResultFormatterPluginInterface;
use Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface;

class SearchResponseFormatter implements SearchResponseFormatterInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface
     */
    protected SearchHttpResponseTransferMapperInterface $httpResponseTransferMapper;

    /**
     * @param \Spryker\Client\SearchHttp\Api\Mapper\SearchHttpResponseTransferMapperInterface $httpResponseTransferMapper
     */
    public function __construct(
        SearchHttpResponseTransferMapperInterface $httpResponseTransferMapper
    ) {
        $this->httpResponseTransferMapper = $httpResponseTransferMapper;
    }

    /**
     * @param array<string, mixed> $responseData
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function format(
        array $responseData,
        array $resultFormatters = [],
        array $requestParameters = []
    ): array {
        $searchHttpResponseTransfer = $this->httpResponseTransferMapper->mapResponseDataToSearchHttpResponseTransfer(
            new SearchHttpResponseTransfer(),
            $responseData,
        );

        $formattedResults = [];
        foreach ($resultFormatters as $resultFormatter) {
            $formattedResults[$resultFormatter->getName()] = $resultFormatter->formatResult($searchHttpResponseTransfer, $requestParameters);
        }

        return $formattedResults;
    }

    /**
     * @param array<string, mixed> $responseData
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function formatSuggestionResponse(
        array $responseData,
        array $resultFormatters = [],
        array $requestParameters = []
    ): array {
        $suggestionsSearchHttpResponseTransfer = $this->httpResponseTransferMapper->mapResponseDataToSuggestionsSearchHttpResponseTransfer(
            new SuggestionsSearchHttpResponseTransfer(),
            $responseData,
        );

        $formattedResults = [];
        foreach ($resultFormatters as $resultFormatter) {
            $result = $resultFormatter->formatResult($suggestionsSearchHttpResponseTransfer, $requestParameters);
            if ($resultFormatter instanceof GroupedResultFormatterPluginInterface) {
                $formattedResults[$resultFormatter->getGroupName()][$resultFormatter->getName()] = $result;

                continue;
            }
            $formattedResults[$resultFormatter->getName()] = $result;
        }

        return $formattedResults;
    }
}
