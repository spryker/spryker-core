<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Sender;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface;
use Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToKernelAppClientInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

class SearchRequestSender implements RequestSenderInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToKernelAppClientInterface
     */
    protected SearchHttpToKernelAppClientInterface $kernelAppClient;

    /**
     * @var \Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface
     */
    protected SearchHeaderBuilderInterface $headerBuilder;

    /**
     * @var \Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface
     */
    protected SearchQueryBuilderInterface $queryBuilder;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToKernelAppClientInterface $kernelAppClient
     * @param \Spryker\Client\SearchHttp\Api\Builder\SearchHeaderBuilderInterface $headerBuilder
     * @param \Spryker\Client\SearchHttp\Api\Builder\SearchQueryBuilderInterface $queryBuilder
     */
    public function __construct(
        SearchHttpToKernelAppClientInterface $kernelAppClient,
        SearchHeaderBuilderInterface $headerBuilder,
        SearchQueryBuilderInterface $queryBuilder
    ) {
        $this->kernelAppClient = $kernelAppClient;
        $this->headerBuilder = $headerBuilder;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function send(QueryInterface $searchQuery, SearchHttpConfigTransfer $searchHttpConfigTransfer): AcpHttpResponseTransfer
    {
        $acpHttpRequestTransfer = (new AcpHttpRequestTransfer())
            ->setMethod(SearchHttpConfig::SEARCH_HTTP_METHOD)
            ->setUri($searchHttpConfigTransfer->getUrlOrFail())
            ->setHeaders($this->headerBuilder->build($searchQuery))
            ->setQuery($this->queryBuilder->build($searchQuery));

        $acpHttpResponseTransfer = $this->kernelAppClient->request($acpHttpRequestTransfer);

        $this->logErrorForFailedResponse($acpHttpRequestTransfer, $acpHttpResponseTransfer);

        return $acpHttpResponseTransfer;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function sendSuggestionRequest(QueryInterface $searchQuery, SearchHttpConfigTransfer $searchHttpConfigTransfer): AcpHttpResponseTransfer
    {
        $acpHttpRequestTransfer = (new AcpHttpRequestTransfer())
            ->setMethod(SearchHttpConfig::SEARCH_HTTP_METHOD)
            ->setUri($searchHttpConfigTransfer->getSuggestionUrlOrFail())
            ->setHeaders($this->headerBuilder->build($searchQuery))
            ->setQuery($this->queryBuilder->build($searchQuery));

        $acpHttpResponseTransfer = $this->kernelAppClient->request($acpHttpRequestTransfer);

        $this->logErrorForFailedResponse($acpHttpRequestTransfer, $acpHttpResponseTransfer);

        return $acpHttpResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     * @param \Generated\Shared\Transfer\AcpHttpResponseTransfer $acpHttpResponseTransfer
     *
     * @return void
     */
    public function logErrorForFailedResponse(AcpHttpRequestTransfer $acpHttpRequestTransfer, AcpHttpResponseTransfer $acpHttpResponseTransfer): void
    {
        if ($acpHttpResponseTransfer->getHttpStatusCode() >= 400) {
            $errorMessage = sprintf(
                'Search request %s %s?%s has failed. Response: %s',
                $acpHttpRequestTransfer->getMethod(),
                $acpHttpRequestTransfer->getUri(),
                http_build_query($acpHttpRequestTransfer->getQuery()),
                $acpHttpResponseTransfer->getContent() ?? 'No response body',
            );
            $this->getLogger()->error($errorMessage);
        }
    }
}
