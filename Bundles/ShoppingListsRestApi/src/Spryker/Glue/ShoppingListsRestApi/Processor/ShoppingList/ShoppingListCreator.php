<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList;

use Generated\Shared\Transfer\RestShoppingListAttributesTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface;

class ShoppingListCreator implements ShoppingListCreatorInterface
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
     * @param \Generated\Shared\Transfer\RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingList(
        RestRequestInterface $restRequest,
        RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResponseWriter->createRestResponse();

        $restShoppingListRequestTransfer = $this->restRequestReader->readRestShoppingListRequestTransferFromRequest(
            $restRequest
        );

        if (count($restShoppingListRequestTransfer->getErrors()) > 0) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $restShoppingListRequestTransfer->getErrors(),
                $restResponse
            );
        }

        $restShoppingListRequestTransfer = $this->shoppingListsResourceMapper->mapRestShoppingListAttributesTransferToRestShoppingListRequestTransfer(
            $restShoppingListAttributesTransfer,
            $restShoppingListRequestTransfer
        );

        $shoppingListResponseTransfer = $this->shoppingListsRestApiClient->createShoppingList($restShoppingListRequestTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->restResponseWriter->writeErrorsFromErrorCodes(
                $shoppingListResponseTransfer->getErrors(),
                $restResponse
            );
        }

        $shoppingListResource = $this->restResponseWriter->createRestResourceFromShoppingListTransfer(
            $shoppingListResponseTransfer->getShoppingList()
        );

        return $restResponse->addResource($shoppingListResource);
    }
}
