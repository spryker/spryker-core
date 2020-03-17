<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;
use Spryker\Shared\SalesReturnsRestApi\SalesReturnsRestApiConfig as SalesReturnsRestApiSharedConfig;

class ReturnReader implements ReturnReaderInterface
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
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface
     */
    protected $returnResourceMapper;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface $returnResourceMapper
     */
    public function __construct(
        SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ReturnResourceMapperInterface $returnResourceMapper
    ) {
        $this->salesReturnClient = $salesReturnClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->returnResourceMapper = $returnResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getReturns(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getReturnDetailAttributes($restRequest);
        }

        return $this->getReturnAttributes($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getReturnAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnFilterTransfer = $this->createReturnFilter($restRequest);
        $returnCollectionTransfer = $this->salesReturnClient->getReturns($returnFilterTransfer);

        $restReturnsAttributesTransfers = $this->returnResourceMapper
            ->mapReturnTransfersToRestReturnsAttributesTransfers($returnCollectionTransfer->getReturns());

        return $this->createRestResponse(
            $returnFilterTransfer,
            $restReturnsAttributesTransfers,
            $returnCollectionTransfer->getPagination()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getReturnDetailAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnFilterTransfer = $this->createReturnFilter($restRequest)
            ->setReturnReference($restRequest->getResource()->getId());

        $returnTransfer = $this->salesReturnClient->getReturns($returnFilterTransfer)
            ->getReturns()
            ->getIterator()
            ->current();

        if (!$returnTransfer) {
            return $this->createErrorRestResponse(SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_RETURN_NOT_FOUND);
        }

        $restReturnDetailsAttributesTransfer = $this->returnResourceMapper
            ->mapReturnTransferToRestReturnDetailsAttributesTransfer(
                $returnTransfer,
                new RestReturnDetailsAttributesTransfer()
            );

        return $this->createDetailRestResponse($restReturnDetailsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     * @param \Generated\Shared\Transfer\RestReturnsAttributesTransfer[] $restReturnsAttributesTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(
        ReturnFilterTransfer $returnFilterTransfer,
        array $restReturnsAttributesTransfers,
        PaginationTransfer $paginationTransfer
    ): RestResponseInterface {
        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                $paginationTransfer->getNbResults(),
                $returnFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($restReturnsAttributesTransfers as $restReturnsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURNS,
                    $restReturnsAttributesTransfer->getReturnReference(),
                    $restReturnsAttributesTransfer
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createDetailRestResponse(RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                SalesReturnsRestApiConfig::RESOURCE_RETURNS,
                $restReturnDetailsAttributesTransfer->getReturnReference(),
                $restReturnDetailsAttributesTransfer
            )
        );

        return $restResponse;
    }

    /**
     * @param string $message
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorRestResponse(string $message): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            $this->returnResourceMapper->mapMessageTransferToRestErrorMessageTransfer(
                (new MessageTransfer())->setValue($message),
                new RestErrorMessageTransfer()
            )
        );

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ReturnFilterTransfer
     */
    protected function createReturnFilter(RestRequestInterface $restRequest): ReturnFilterTransfer
    {
        $filterTransfer = new FilterTransfer();

        if ($restRequest->getPage()) {
            $filterTransfer
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit() ?? 0);
        }

        return (new ReturnFilterTransfer())
            ->fromArray($restRequest->getHttpRequest()->query->all(), true)
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setFilter($filterTransfer);
    }
}
