<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList;

use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface;

class ShoppingListReader implements ShoppingListReaderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListMapperInterface
     */
    protected $shoppingListsResourceMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface
     */
    protected $restRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface
     */
    protected $restResponseWriter;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListMapperInterface $shoppingListsResourceMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface $restRequestReader
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface $restResponseWriter
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListMapperInterface $shoppingListsResourceMapper,
        RestRequestReaderInterface $restRequestReader,
        RestResponseWriterInterface $restResponseWriter
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListsResourceMapper = $shoppingListsResourceMapper;
        $this->restRequestReader = $restRequestReader;
        $this->restResponseWriter = $restResponseWriter;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerShoppingListCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResponseWriter->createRestResponse();
        $customerResponseTransfer = $this->restRequestReader->readCustomerResponseTransferFromRequest($restRequest);

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $this->restRequestReader->mapCustomerResponseErrorsToErrorsCodes(
                    $customerResponseTransfer->getErrors()->getArrayCopy()
                ),
                $restResponse
            );
        }

        $shoppingListCollectionTransfer = $this->shoppingListsRestApiClient->getCustomerShoppingListCollection(
            $customerResponseTransfer->getCustomerTransfer()
        );

        foreach ($shoppingListCollectionTransfer->getShoppingLists() as $shoppingListTransfer) {
            $restResponse->addResource(
                $this->restResponseWriter->createRestResourceFromShoppingListTransfer($shoppingListTransfer)
            );
        }

        return $restResponse;
    }

    /**
     * @param string $uuidShoppingList
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerShoppingList(
        string $uuidShoppingList,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restResponse = $this->restResponseWriter->createRestResponse();

        $restShoppingListRequestTransfer = $this->restRequestReader->readRestShoppingListRequestTransferWithUuidFromRequest(
            $uuidShoppingList,
            $restRequest
        );

        if (count($restShoppingListRequestTransfer->getErrors()) > 0) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $restShoppingListRequestTransfer->getErrors(),
                $restResponse
            );
        }

        $shoppingListResponseTransfer = $this->shoppingListsRestApiClient->findShoppingListByUuid($restShoppingListRequestTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $shoppingListResponseTransfer->getErrors(),
                $restResponse
            );
        }

        $shoppingListResource = $this->restResponseWriter->createRestResourceFromShoppingListTransfer(
            $shoppingListResponseTransfer->getShoppingList()
        );
        $this->restResponseWriter->writeRelationsIntoShoppingListResource(
            $shoppingListResponseTransfer->getShoppingList(),
            $shoppingListResource
        );

        return $restResponse->addResource($shoppingListResource);
    }
}
