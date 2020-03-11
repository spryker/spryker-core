<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class ReturnReasonReader implements ReturnReasonReaderInterface
{
    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface
     */
    protected $returnReasonResourceMapper;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface $returnReasonResourceMapper
     */
    public function __construct(
        SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ReturnReasonResourceMapperInterface $returnReasonResourceMapper
    ) {
        $this->salesReturnClient = $salesReturnClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->returnReasonResourceMapper = $returnReasonResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getReturnReasons(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnReasonFilterTransfer = $this->createReturnReasonFilter($restRequest);

        $returnReasonTransfers = $this->salesReturnClient
            ->getReturnReasons($returnReasonFilterTransfer)
            ->getReturnReasons();

        $restReturnReasonsAttributesTransfers = $this->returnReasonResourceMapper
            ->mapReturnReasonTransfersToRestReturnReasonsAttributesTransfers(
                $returnReasonTransfers,
                $restRequest->getMetadata()->getLocale()
            );

        return $this->createRestResponse($returnReasonFilterTransfer, $restReturnReasonsAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     * @param \Generated\Shared\Transfer\RestReturnReasonsAttributesTransfer[] $restReturnReasonsAttributesTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(
        ReturnReasonFilterTransfer $returnReasonFilterTransfer,
        array $restReturnReasonsAttributesTransfers
    ): RestResponseInterface {
        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                count($restReturnReasonsAttributesTransfers),
                $returnReasonFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($restReturnReasonsAttributesTransfers as $restReturnReasonsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURN_REASONS,
                    null,
                    $restReturnReasonsAttributesTransfer
                )
            );
        }

        return $restResponse;
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
