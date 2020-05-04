<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface;

class ReturnReasonReader implements ReturnReasonReaderInterface
{
    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface
     */
    protected $restReturnReasonResponseBuilder;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface $restReturnReasonResponseBuilder
     */
    public function __construct(
        SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient,
        RestReturnReasonResponseBuilderInterface $restReturnReasonResponseBuilder
    ) {
        $this->salesReturnClient = $salesReturnClient;
        $this->restReturnReasonResponseBuilder = $restReturnReasonResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getReturnReasons(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnReasonFilterTransfer = $this->createReturnReasonFilter($restRequest);
        $returnReasonCollectionTransfer = $this->salesReturnClient->getReturnReasons($returnReasonFilterTransfer);

        return $this->restReturnReasonResponseBuilder->createReturnReasonListRestResponse(
            $returnReasonFilterTransfer,
            $returnReasonCollectionTransfer,
            $restRequest->getMetadata()->getLocale()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ReturnReasonFilterTransfer
     */
    protected function createReturnReasonFilter(RestRequestInterface $restRequest): ReturnReasonFilterTransfer
    {
        $filterTransfer = new FilterTransfer();

        if ($restRequest->getPage()) {
            $filterTransfer
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit() ?? 0);
        }

        return (new ReturnReasonFilterTransfer())
            ->setFilter($filterTransfer);
    }
}
