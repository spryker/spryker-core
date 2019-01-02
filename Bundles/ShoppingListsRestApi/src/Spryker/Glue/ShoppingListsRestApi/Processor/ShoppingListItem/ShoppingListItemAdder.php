<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemMapperInterface
     */
    protected $shoppingListItemsResourceMapper;

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
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemMapperInterface $shoppingListItemsResourceMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface $restRequestReader
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface $restResponseWriter
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListItemMapperInterface $shoppingListItemsResourceMapper,
        RestRequestReaderInterface $restRequestReader,
        RestResponseWriterInterface $restResponseWriter
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListItemsResourceMapper = $shoppingListItemsResourceMapper;
        $this->restRequestReader = $restRequestReader;
        $this->restResponseWriter = $restResponseWriter;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addShoppingListItem(
        RestRequestInterface $restRequest,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResponseWriter->createRestResponse();
        $restShoppingListItemRequestTransfer = $this->restRequestReader->readRestShoppingListItemRequestTransferFromRequest(
            $restRequest
        );

        if (count($restShoppingListItemRequestTransfer->getErrors()) > 0) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $restShoppingListItemRequestTransfer->getErrors(),
                $restResponse
            );
        }

        $restShoppingListItemRequestTransfer = $this->shoppingListItemsResourceMapper->mapRestShoppingListItemAttributesTransferToRestShoppingListItemRequestTransfer(
            $restShoppingListItemAttributesTransfer,
            $restShoppingListItemRequestTransfer
        );

        $shoppingListItemResponseTransfer = $this->shoppingListsRestApiClient->addItem($restShoppingListItemRequestTransfer);

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $shoppingListItemResponseTransfer->getErrors(),
                $restResponse
            );
        }

        $shoppingListItemResource = $this->restResponseWriter->createRestResourceFromShoppingListItemTransfer(
            $shoppingListItemResponseTransfer->getShoppingListItem(),
            $restShoppingListItemRequestTransfer->getShoppingListUuid()
        );

        return $restResponse->addResource($shoppingListItemResource);
    }
}
