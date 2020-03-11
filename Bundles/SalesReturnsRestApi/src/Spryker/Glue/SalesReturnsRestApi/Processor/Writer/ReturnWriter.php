<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Writer;

use ArrayObject;
use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnRequestAttributesTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;
use Spryker\Shared\SalesReturnsRestApi\SalesReturnsRestApiConfig as SalesReturnsRestApiSharedConfig;

class ReturnWriter implements ReturnWriterInterface
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
     * @param \Generated\Shared\Transfer\RestReturnRequestAttributesTransfer $restReturnRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturn(
        RestRequestInterface $restRequest,
        RestReturnRequestAttributesTransfer $restReturnRequestAttributesTransfer
    ): RestResponseInterface {
        $returnFilterTransfer = $this->createReturnRequest($restRequest, $restReturnRequestAttributesTransfer);

        $returnResponseTransfer = $this->salesReturnClient->createReturn($returnFilterTransfer);

        if (!$returnResponseTransfer->getIsSuccessful()) {
            return $this->createErrorRestResponse(SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CREATE_RETURN);
        }

        $restReturnDetailsAttributesTransfer = $this->returnResourceMapper
            ->mapReturnTransferToRestReturnDetailsAttributesTransfer(
                $returnResponseTransfer->getReturn(),
                new RestReturnDetailsAttributesTransfer()
            );

        return $this->createRestResponse($restReturnDetailsAttributesTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestReturnRequestAttributesTransfer $restReturnRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CreateReturnRequestTransfer
     */
    protected function createReturnRequest(
        RestRequestInterface $restRequest,
        RestReturnRequestAttributesTransfer $restReturnRequestAttributesTransfer
    ): CreateReturnRequestTransfer {
        $returnItemTransfers = [];

        foreach ($restReturnRequestAttributesTransfer->getReturnItems() as $itemRequestAttributesTransfer) {
            $returnItemTransfers[] = (new ReturnItemTransfer())
                ->fromArray($itemRequestAttributesTransfer->toArray(), true)
                ->setOrderItem((new ItemTransfer())->setUuid($itemRequestAttributesTransfer->getSalesOrderItemUuid()));
        }

        return (new CreateReturnRequestTransfer())
            ->setStore($restReturnRequestAttributesTransfer->getStore())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier()))
            ->setReturnItems(new ArrayObject($returnItemTransfers));
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
     * @param \Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer): RestResponseInterface
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
}
