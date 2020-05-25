<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnSearchClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class ReturnReasonReader implements ReturnReasonReaderInterface
{
    public const DEFAULT_ELASTICSEARCH_LIMIT = 10000;

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

        /** @var \Generated\Shared\Transfer\ReturnReasonSearchCollectionTransfer $returnReasonSearchPageCollectionTransfer */
        $returnReasonSearchPageCollectionTransfer = $searchResults[static::KEY_RETURN_REASON_COLLECTION];

        return $this->restReturnReasonResponseBuilder->createReturnReasonListRestResponse(
            $returnReasonSearchRequestTransfer,
            $returnReasonSearchPageCollectionTransfer,
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
        $filterTransfer = new FilterTransfer();

        if ($restRequest->getPage()) {
            $filterTransfer
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit() ?? static::DEFAULT_ELASTICSEARCH_LIMIT);
        }

        return (new ReturnReasonSearchRequestTransfer())->setFilter($filterTransfer);
    }
}
