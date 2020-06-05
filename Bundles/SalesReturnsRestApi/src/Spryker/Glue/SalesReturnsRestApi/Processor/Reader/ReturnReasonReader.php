<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnSearchClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface;

class ReturnReasonReader implements ReturnReasonReaderInterface
{
    /**
     * @uses \Spryker\Client\SalesReturnSearch\Plugin\Elasticsearch\Query\PaginatedReturnReasonSearchQueryExpanderPlugin::PARAMETER_OFFSET
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\SalesReturnSearch\Plugin\Elasticsearch\Query\PaginatedReturnReasonSearchQueryExpanderPlugin::PARAMETER_LIMIT
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * @uses \Spryker\Client\SalesReturnSearch\Plugin\Elasticsearch\ResultFormatter\ReturnReasonSearchResultFormatterPlugin::NAME
     */
    protected const KEY_RETURN_REASON_COLLECTION = 'ReturnReasonCollection';

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnSearchClientInterface
     */
    protected $salesReturnSearchClient;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface
     */
    protected $restReturnReasonResponseBuilder;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnSearchClientInterface $salesReturnSearchClient
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface $restReturnReasonResponseBuilder
     */
    public function __construct(
        SalesReturnsRestApiToSalesReturnSearchClientInterface $salesReturnSearchClient,
        RestReturnReasonResponseBuilderInterface $restReturnReasonResponseBuilder
    ) {
        $this->salesReturnSearchClient = $salesReturnSearchClient;
        $this->restReturnReasonResponseBuilder = $restReturnReasonResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getReturnReasons(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnReasonSearchRequestTransfer = $this->createReturnReasonSearchRequest($restRequest);
        $searchResults = $this->salesReturnSearchClient->searchReturnReasons(
            $returnReasonSearchRequestTransfer
        );

        /** @var \Generated\Shared\Transfer\ReturnReasonSearchCollectionTransfer $returnReasonSearchCollectionTransfer */
        $returnReasonSearchCollectionTransfer = $searchResults[static::KEY_RETURN_REASON_COLLECTION];

        return $this->restReturnReasonResponseBuilder->createReturnReasonListRestResponse(
            $returnReasonSearchRequestTransfer,
            $returnReasonSearchCollectionTransfer,
            $restRequest->getMetadata()->getLocale()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer
     */
    protected function createReturnReasonSearchRequest(RestRequestInterface $restRequest): ReturnReasonSearchRequestTransfer
    {
        $requestParameters = [];
        $page = $restRequest->getPage();

        if ($page) {
            $requestParameters[static::PARAMETER_OFFSET] = $page->getOffset();
            $requestParameters[static::PARAMETER_LIMIT] = $page->getLimit();
        }

        return (new ReturnReasonSearchRequestTransfer())->setRequestParameters($requestParameters);
    }
}
