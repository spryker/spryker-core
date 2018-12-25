<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShoppingListItemRestResponseBuilder implements ShoppingListItemRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface
     */
    protected $shoppingListItemResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemsResourceMapperInterface $shoppingListItemResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListItemsResourceMapperInterface $shoppingListItemResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shoppingListItemResourceMapper = $shoppingListItemResourceMapper;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestErrorMessageTransfer[] $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddItemErrorResponse(ArrayObject $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $restErrorMessageTransfer) {
            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingListBadRequestErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_ID_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param string $idShoppingList
     * @param string $idShoppingListItem
     *
     * @return string
     */
    protected function createSelfLinkForShoppingListItem(
        string $idShoppingList,
        string $idShoppingListItem
    ): string {
        return sprintf(
            ShoppingListsRestApiConfig::FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            $idShoppingList,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $idShoppingListItem
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param string $idShoppingList
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingListItemResponse(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        string $idShoppingList
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restShoppingListItemAttributesTransfer = $this->shoppingListItemResourceMapper->mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
            $shoppingListItemTransfer,
            new RestShoppingListItemAttributesTransfer()
        );

        $shoppingListItemResource = $this->restResourceBuilder->createRestResource(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $shoppingListItemTransfer->getUuid(),
            $restShoppingListItemAttributesTransfer
        );

        $shoppingListItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForShoppingListItem(
                $idShoppingList,
                $shoppingListItemTransfer->getUuid()
            )
        );

        return $restResponse->addResource($shoppingListItemResource);
    }
}
