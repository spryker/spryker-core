<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface;

class ShoppingListItemDeleter implements ShoppingListItemDeleterInterface
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
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteShoppingListItem(
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restResponse = $this->restResponseWriter->createRestResponse();
        $restShoppingListItemRequestTransfer = $this->restRequestReader->readRestShoppingListItemRequestTransferWithUuidFromRequest(
            $restRequest
        );

        if (count($restShoppingListItemRequestTransfer->getErrorCodes()) > 0) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $restShoppingListItemRequestTransfer->getErrorCodes(),
                $restResponse
            );
        }

        $shoppingListItemResponseTransfer = $this->shoppingListsRestApiClient->deleteShoppingListItem($restShoppingListItemRequestTransfer);

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $shoppingListItemResponseTransfer->getErrors(),
                $restResponse
            );
        }

        return $restResponse;
    }
}
