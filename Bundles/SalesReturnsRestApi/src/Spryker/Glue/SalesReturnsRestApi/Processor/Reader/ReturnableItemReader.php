<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;
use Spryker\Shared\SalesReturnsRestApi\SalesReturnsRestApiConfig as SalesReturnsRestApiSharedConfig;

class ReturnableItemReader implements ReturnableItemReaderInterface
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
    public function getReturnableItems(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getReturnableItem($restRequest);
        }

        return $this->getReturnableItemAttributes($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getReturnableItemAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnableItemFilterTransfer = $this->createReturnableItemFilter($restRequest);
        $itemCollectionTransfer = $this->salesReturnClient->getReturnableItems($returnableItemFilterTransfer);

        $restOrderItemsAttributesTransfers = $this->returnResourceMapper
            ->mapItemCollectionTransferToRestOrderItemsAttributesTransfers($itemCollectionTransfer);

        return $this->createRestResponse($restOrderItemsAttributesTransfers);
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
            return $this->createErrorRestResponse(SalesReturnsRestApiSharedConfig::ERROR_IDENTIFIER_RETURNABLE_ITEM_NOT_FOUND);
        }

        $restOrderItemsAttributesTransfer = $this->returnResourceMapper
            ->mapItemTransferToRestOrderItemsAttributesTransfer(
                $itemTransfer,
                new RestOrderItemsAttributesTransfer()
            );

        return $this->createDetailRestResponse($restOrderItemsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer[] $restOrderItemsAttributesTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(
        array $restOrderItemsAttributesTransfers
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($restOrderItemsAttributesTransfers as $restOrderItemsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURNABLE_ITEMS,
                    $restOrderItemsAttributesTransfer->getUuid(),
                    $restOrderItemsAttributesTransfer
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createDetailRestResponse(RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                SalesReturnsRestApiConfig::RESOURCE_RETURNABLE_ITEMS,
                $restOrderItemsAttributesTransfer->getUuid(),
                $restOrderItemsAttributesTransfer
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
     * @return \Generated\Shared\Transfer\ReturnableItemFilterTransfer
     */
    protected function createReturnableItemFilter(RestRequestInterface $restRequest): ReturnableItemFilterTransfer
    {
        return (new ReturnableItemFilterTransfer())
            ->fromArray($restRequest->getHttpRequest()->query->all(), true)
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
    }
}
