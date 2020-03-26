<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Writer;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnRequestAttributesTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestResponseBuilderInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Shared\SalesReturnsRestApi\SalesReturnsRestApiConfig as SalesReturnsRestApiSharedConfig;

class ReturnWriter implements ReturnWriterInterface
{
    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestResponseBuilderInterface
     */
    protected $restResponseBuilder;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface
     */
    protected $returnResourceMapper;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestResponseBuilderInterface $restResponseBuilder
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface $returnResourceMapper
     */
    public function __construct(
        SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient,
        RestResponseBuilderInterface $restResponseBuilder,
        ReturnResourceMapperInterface $returnResourceMapper
    ) {
        $this->salesReturnClient = $salesReturnClient;
        $this->restResponseBuilder = $restResponseBuilder;
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
            return $this->restResponseBuilder->createErrorRestResponse(SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CREATE_RETURN);
        }

        $restReturnDetailsAttributesTransfer = $this->returnResourceMapper
            ->mapReturnTransferToRestReturnDetailsAttributesTransfer(
                $returnResponseTransfer->getReturn(),
                new RestReturnDetailsAttributesTransfer()
            );

        return $this->restResponseBuilder->createReturnDetailRestResponse($restReturnDetailsAttributesTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestReturnRequestAttributesTransfer $restReturnRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function createReturnRequest(
        RestRequestInterface $restRequest,
        RestReturnRequestAttributesTransfer $restReturnRequestAttributesTransfer
    ): ReturnCreateRequestTransfer {
        $returnItemTransfers = [];

        foreach ($restReturnRequestAttributesTransfer->getReturnItems() as $itemRequestAttributesTransfer) {
            $returnItemTransfers[] = (new ReturnItemTransfer())
                ->fromArray($itemRequestAttributesTransfer->toArray(), true)
                ->setOrderItem((new ItemTransfer())->setUuid($itemRequestAttributesTransfer->getSalesOrderItemUuid()));
        }

        return (new ReturnCreateRequestTransfer())
            ->setStore($restReturnRequestAttributesTransfer->getStore())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier()))
            ->setReturnItems(new ArrayObject($returnItemTransfers));
    }
}
