<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShoppingListItemRestResponseBuilder implements ShoppingListItemRestResponseBuilderInterface
{
    public const FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestErrorMessageTransfer[] $restErrorMessages
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddItemErrorResponse(ArrayObject $restErrorMessages): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($restErrorMessages as $restErrorMessageTransfer) {
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
            static::FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            $idShoppingList,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $idShoppingListItem
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     * @param string $idShoppingList
     * @param string $idShoppingListItem
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingListItemResponse(
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer,
        string $idShoppingList,
        string $idShoppingListItem
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $shoppingListItemResource = $this->restResourceBuilder->createRestResource(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $idShoppingListItem,
            $restShoppingListItemAttributesTransfer
        );

        $shoppingListItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForShoppingListItem(
                $idShoppingList,
                $idShoppingListItem
            )
        );

        return $restResponse->addResource($shoppingListItemResource);
    }
}
