<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListItemRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemMapperInterface
     */
    protected $shoppingListItemMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListItemRestRequestReaderInterface
     */
    protected $shoppingListItemRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface
     */
    protected $shoppingListItemRestResponseBuilder;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemMapperInterface $shoppingListItemMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListItemRestRequestReaderInterface $shoppingListItemRestRequestReader
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListItemMapperInterface $shoppingListItemMapper,
        ShoppingListItemRestRequestReaderInterface $shoppingListItemRestRequestReader,
        ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListItemMapper = $shoppingListItemMapper;
        $this->shoppingListItemRestRequestReader = $shoppingListItemRestRequestReader;
        $this->shoppingListItemRestResponseBuilder = $shoppingListItemRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addShoppingListItem(
        RestRequestInterface $restRequest,
        RestShoppingListItemsAttributesTransfer $restShoppingListItemsAttributesTransfer
    ): RestResponseInterface {
        $shoppingListItemRequestTransfer = $this->shoppingListItemRestRequestReader->readShoppingListItemRequestTransferFromRequest(
            $restRequest,
        );

        if (count($shoppingListItemRequestTransfer->getErrorIdentifiers()) > 0) {
            return $this->shoppingListItemRestResponseBuilder->buildErrorRestResponse(
                $restRequest,
                $shoppingListItemRequestTransfer->getErrorIdentifiers(),
            );
        }

        $shoppingListItemRequestTransfer = $this->shoppingListItemMapper->mapRestShoppingListItemsAttributesTransferToShoppingListItemRequestTransfer(
            $restShoppingListItemsAttributesTransfer,
            $shoppingListItemRequestTransfer,
        );

        $shoppingListItemResponseTransfer = $this->shoppingListsRestApiClient->addShoppingListItem($shoppingListItemRequestTransfer);

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListItemRestResponseBuilder->buildErrorRestResponse(
                $restRequest,
                $shoppingListItemResponseTransfer->getErrors(),
            );
        }

        return $this->shoppingListItemRestResponseBuilder->buildShoppingListItemRestResponse(
            $shoppingListItemResponseTransfer->getShoppingListItem(),
            $shoppingListItemRequestTransfer->getShoppingListUuid(),
        );
    }
}
