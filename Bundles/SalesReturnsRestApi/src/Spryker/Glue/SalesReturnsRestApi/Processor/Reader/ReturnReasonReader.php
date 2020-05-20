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
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnPageSearchClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class ReturnReasonReader implements ReturnReasonReaderInterface
{
    /**
     * @uses \Spryker\Client\SalesReturnPageSearch\Plugin\Elasticsearch\ResultFormatter\ReturnReasonSearchResultFormatterPlugin::NAME
     */
    protected const KEY_RETURN_REASON_COLLECTION = 'ReturnReasonCollection';

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnPageSearchClientInterface
     */
    protected $salesReturnPageSearchClient;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface
     */
    protected $restReturnReasonResponseBuilder;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnPageSearchClientInterface $salesReturnPageSearchClient
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface $restReturnReasonResponseBuilder
     */
    public function __construct(
        SalesReturnsRestApiToSalesReturnPageSearchClientInterface $salesReturnPageSearchClient,
        RestReturnReasonResponseBuilderInterface $restReturnReasonResponseBuilder
    ) {
        $this->salesReturnPageSearchClient = $salesReturnPageSearchClient;
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
        $searchResults = $this->salesReturnPageSearchClient->searchReturnReasons(
            $returnReasonSearchRequestTransfer
        );

        /** @var \Generated\Shared\Transfer\ReturnReasonPageSearchCollectionTransfer $returnReasonSearchPageCollectionTransfer */
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
                ->setLimit($restRequest->getPage()->getLimit() ?? SalesReturnsRestApiConfig::DEFAULT_ELASTICSEARCH_LIMIT);
        }

        return (new ReturnReasonSearchRequestTransfer())->setFilter($filterTransfer);
    }
}
