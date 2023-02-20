<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
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
     * @var \Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatterInterface
     */
    protected SearchResponseFormatterInterface $searchResponseFormatter;

    /**
     * @param \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface $configReader
     * @param \Spryker\Client\SearchHttp\Api\Sender\RequestSenderInterface $searchRequestSender
     * @param \Spryker\Client\SearchHttp\Api\Formatter\SearchResponseFormatterInterface $searchResponseFormatter
     */
    public function __construct(
        ConfigReaderInterface $configReader,
        RequestSenderInterface $searchRequestSender,
        SearchResponseFormatterInterface $searchResponseFormatter
    ) {
        $this->configReader = $configReader;
        $this->searchRequestSender = $searchRequestSender;
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

            $httpResponse = $this->searchRequestSender->send($searchQuery, $searchHttpConfigTransfer);

            return $this->searchResponseFormatter->format($httpResponse, $resultFormatters, $requestParameters);
        } catch (Throwable $throwable) {
            $this->getLogger()->error($throwable->getMessage(), $throwable->getTrace());

            return [];
        }
    }
}
