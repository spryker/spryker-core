<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface
     */
    protected $shoppingListItemResourceMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface
     */
    protected $shoppingListItemRestResponseBuilder;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface $shoppingListItemResourceMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListItemsResourceMapperInterface $shoppingListItemResourceMapper,
        ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListItemResourceMapper = $shoppingListItemResourceMapper;
        $this->shoppingListItemRestResponseBuilder = $shoppingListItemRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItem(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idShoppingList = $this->findShoppingListIdentifier($restRequest);
        if (!$idShoppingList) {
            return $this->shoppingListItemRestResponseBuilder->createShoppingListBadRequestErrorResponse();
        }

        $restShoppingListItemRequestTransfer = $this->shoppingListItemResourceMapper->mapRestRequestToRestShoppingListItemRequestTransfer(
            $restRequest,
            (new RestShoppingListItemRequestTransfer())->setShoppingListUuid($idShoppingList)
        );

        $restShoppingListItemResponseTransfer = $this->shoppingListsRestApiClient->addItem($restShoppingListItemRequestTransfer);

        if (!$restShoppingListItemResponseTransfer->getIsSuccess()) {
            return $this->shoppingListItemRestResponseBuilder->createAddItemErrorResponse(
                $restShoppingListItemResponseTransfer->getErrors()
            );
        }

        return $this->shoppingListItemRestResponseBuilder->createShoppingListItemResponse(
            $restShoppingListItemResponseTransfer->getShoppingListItem(),
            $idShoppingList
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findShoppingListIdentifier(RestRequestInterface $restRequest): ?string
    {
        $shoppingListResource = $restRequest->findParentResourceByType(ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS);
        if ($shoppingListResource !== null) {
            return $shoppingListResource->getId();
        }

        return null;
    }
}
