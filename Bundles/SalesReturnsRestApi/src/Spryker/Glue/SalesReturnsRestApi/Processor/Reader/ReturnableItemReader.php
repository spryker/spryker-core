<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestResponseBuilderInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Shared\SalesReturnsRestApi\SalesReturnsRestApiConfig as SalesReturnsRestApiSharedConfig;

class ReturnableItemReader implements ReturnableItemReaderInterface
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
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getReturnableItems(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getReturnableItem($restRequest);
        }

        return $this->getReturnableItemsAttributes($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getReturnableItemsAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnableItemFilterTransfer = $this->createReturnableItemFilter($restRequest);
        $itemCollectionTransfer = $this->salesReturnClient->getReturnableItems($returnableItemFilterTransfer);

        $restOrderItemsAttributesTransfers = $this->returnResourceMapper
            ->mapItemCollectionTransferToRestOrderItemsAttributesTransfers($itemCollectionTransfer);

        return $this->restResponseBuilder->createReturnableItemListRestResponse($restOrderItemsAttributesTransfers);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getReturnableItem(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnableItemFilterTransfer = $this->createReturnableItemFilter($restRequest)
            ->addSalesOrderItemUuid($restRequest->getResource()->getId());

        $itemTransfer = $this->salesReturnClient->getReturnableItems($returnableItemFilterTransfer)
            ->getItems()
            ->getIterator()
            ->current();

        if (!$itemTransfer) {
            return $this->restResponseBuilder->createErrorRestResponse(SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_RETURNABLE_ITEM_NOT_FOUND);
        }

        $restOrderItemsAttributesTransfer = $this->returnResourceMapper
            ->mapItemTransferToRestOrderItemsAttributesTransfer(
                $itemTransfer,
                new RestOrderItemsAttributesTransfer()
            );

        return $this->restResponseBuilder->createReturnableItemDetailRestResponse($restOrderItemsAttributesTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ReturnableItemFilterTransfer
     */
    protected function createReturnableItemFilter(RestRequestInterface $restRequest): ReturnableItemFilterTransfer
    {
        return (new ReturnableItemFilterTransfer())
            ->fromArray($restRequest->getHttpRequest()->query->all(), true)
            ->addCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
    }
}
