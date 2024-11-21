<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\Api\Decoder\SearchResponseDecoderInterface;
use Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatterInterface;
use Spryker\Client\SearchHttp\Api\Sender\RequestSenderInterface;
use Spryker\Client\SearchHttp\Reader\ConfigReaderInterface;
use Spryker\Shared\Log\LoggerTrait;
use Throwable;

class SearchHttpApiClient implements SearchHttpApiInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface
     */
    protected ConfigReaderInterface $configReader;

    /**
     * @var \Spryker\Client\SearchHttp\Api\Sender\RequestSenderInterface
     */
    protected RequestSenderInterface $searchRequestSender;

    /**
     * @var \Spryker\Client\SearchHttp\Api\Decoder\SearchResponseDecoderInterface
     */
    protected SearchResponseDecoderInterface $searchResponseDecoder;

    /**
     * @var \Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatterInterface
     */
    protected SearchResponseFormatterInterface $searchResponseFormatter;

    /**
     * @param \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface $configReader
     * @param \Spryker\Client\SearchHttp\Api\Sender\RequestSenderInterface $searchRequestSender
     * @param \Spryker\Client\SearchHttp\Api\Decoder\SearchResponseDecoderInterface $searchResponseDecoder
     * @param \Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatterInterface $searchResponseFormatter
     */
    public function __construct(
        ConfigReaderInterface $configReader,
        RequestSenderInterface $searchRequestSender,
        SearchResponseDecoderInterface $searchResponseDecoder,
        SearchResponseFormatterInterface $searchResponseFormatter
    ) {
        $this->configReader = $configReader;
        $this->searchRequestSender = $searchRequestSender;
        $this->searchResponseDecoder = $searchResponseDecoder;
        $this->searchResponseFormatter = $searchResponseFormatter;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): array
    {
        try {
            $searchHttpConfigTransfer = $this->configReader
                ->getSearchHttpConfigCollectionForCurrentStore()
                ->getSearchHttpConfigs()
                ->getIterator()
                ->current();

            $acpHttpResponseTransfer = $this->searchRequestSender->send($searchQuery, $searchHttpConfigTransfer);
            $responseData = $this->searchResponseDecoder->decode($acpHttpResponseTransfer);

            if (!$resultFormatters) {
                return $responseData;
            }

            return $this->searchResponseFormatter->format($responseData, $resultFormatters, $requestParameters);
        } catch (Throwable $throwable) {
            $this->getLogger()->error($throwable->getMessage(), $throwable->getTrace());

            return [];
        }
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function suggestSearch(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): array
    {
        try {
            $searchHttpConfigTransfer = $this->configReader
                ->getSearchHttpConfigCollectionForCurrentStore()
                ->getSearchHttpConfigs()
                ->getIterator()
                ->current();

            $acpHttpResponseTransfer = $this->searchRequestSender->sendSuggestionRequest($searchQuery, $searchHttpConfigTransfer);
            $responseData = $this->searchResponseDecoder->decode($acpHttpResponseTransfer);

            if (!$resultFormatters) {
                return $responseData;
            }

            return $this->searchResponseFormatter->formatSuggestionResponse($responseData, $resultFormatters, $requestParameters);
        } catch (Throwable $throwable) {
            $this->getLogger()->error($throwable->getMessage(), $throwable->getTrace());

            return [];
        }
    }
}
