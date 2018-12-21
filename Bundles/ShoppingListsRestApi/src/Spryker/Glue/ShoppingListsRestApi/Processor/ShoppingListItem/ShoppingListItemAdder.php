<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    protected const SELF_LINK_FORMAT_PATTERN = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemResourceMapperInterface
     */
    protected $shoppingListItemResourceMapper;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Mapper\ShoppingListItemResourceMapperInterface $shoppingListItemResourceMapper
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListItemResourceMapperInterface $shoppingListItemResourceMapper
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shoppingListItemResourceMapper = $shoppingListItemResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItem(
        RestRequestInterface $restRequest,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $shoppingListUuid = $this->findShoppingListIdentifier($restRequest);
        if (!$shoppingListUuid) {
            return $this->createShoppingListNotFoundErrorResponse();
        }

        $shoppingListItemTransfer = $this->shoppingListItemResourceMapper->mapShoppingListItemTransferFromRestRequest(
            $restRequest,
            $restShoppingListItemAttributesTransfer
        );

        $restShoppingListItemRequestTransfer = $this->shoppingListItemResourceMapper->mapRestShoppingListItemRequestTransferFromRestRequest(
            $restRequest,
            $shoppingListItemTransfer
        );

        $shoppingListItemTransfer = $this->shoppingListsRestApiClient->addItem($restShoppingListItemRequestTransfer);

        if (!$shoppingListItemTransfer->getIdShoppingListItem()) {
            return $this->createShoppingListCanNotAddItemErrorResponse();
        }

        $restShoppingListItemAttributesTransfer = $this->shoppingListItemResourceMapper->mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
            $shoppingListItemTransfer
        );

        $shoppingListItemResource = $this->restResourceBuilder->createRestResource(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $shoppingListItemTransfer->getUuid(),
            $restShoppingListItemAttributesTransfer
        );

        $shoppingListItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForShoppingListItem(
                $shoppingListUuid,
                $shoppingListItemTransfer->getUuid()
            )
        );

        return $restResponse->addResource($shoppingListItemResource);
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

    /**
     * @param string $shoppingListResourceId
     * @param string $shoppingListItemResourceId
     *
     * @return string
     */
    protected function createSelfLinkForShoppingListItem(
        string $shoppingListResourceId,
        string $shoppingListItemResourceId
    ): string {
        return sprintf(
            static::SELF_LINK_FORMAT_PATTERN,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            $shoppingListResourceId,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $shoppingListItemResourceId
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createShoppingListNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createShoppingListCanNotAddItemErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_CANNOT_ADD_ITEM)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_CANNOT_ADD_ITEM);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }
}
