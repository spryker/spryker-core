<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Response;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestShoppingListAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class RestResponseWriter implements RestResponseWriterInterface
{
    protected const FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListsResourceMapperInterface
     */
    protected $shoppingListsResourceMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemsResourceMapperInterface
     */
    protected $shoppingListItemsResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListsResourceMapperInterface $shoppingListsResourceMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemsResourceMapperInterface $shoppingListItemsResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListsResourceMapperInterface $shoppingListsResourceMapper,
        ShoppingListItemsResourceMapperInterface $shoppingListItemsResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shoppingListsResourceMapper = $shoppingListsResourceMapper;
        $this->shoppingListItemsResourceMapper = $shoppingListItemsResourceMapper;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param string[] $errorCodes
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function writeErrorsFromErrorCodes(
        array $errorCodes,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        foreach ($errorCodes as $errorCode) {
            $errorSignature = ShoppingListsRestApiConfig::RESPONSE_ERROR_MAP[$errorCode] ?? [
                    'status' => ShoppingListsRestApiConfig::RESPONSE_UNEXPECTED_HTTP_STATUS,
                    'detail' => $errorCode,
                ];

            $restResponse->addError(
                (new RestErrorMessageTransfer())
                    ->setCode($errorCode)
                    ->setDetail($errorSignature['detail'])
                    ->setStatus($errorSignature['status'])
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createRestResourceFromShoppingListTransfer(
        ShoppingListTransfer $shoppingListTransfer
    ): RestResourceInterface {
        $restShoppingListsAttributesTransfer = $this->shoppingListsResourceMapper->mapShoppingListTransferToRestShoppingListsAttributesTransfer(
            $shoppingListTransfer,
            new RestShoppingListAttributesTransfer()
        );

        $shoppingListResource = $this->restResourceBuilder->createRestResource(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            $shoppingListTransfer->getUuid(),
            $restShoppingListsAttributesTransfer
        );

        return $shoppingListResource;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $shoppingListResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function writeRelationsIntoShoppingListResource(
        ShoppingListTransfer $shoppingListTransfer,
        RestResourceInterface $shoppingListResource
    ): RestResourceInterface {
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListResource->addRelationship(
                $this->createRestResourceFromShoppingListItemTransfer(
                    $shoppingListItemTransfer,
                    $shoppingListTransfer->getUuid()
                )
            );
        }

        return $shoppingListResource;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param string $idShoppingList
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createRestResourceFromShoppingListItemTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        string $idShoppingList
    ): RestResourceInterface {
        $restShoppingListItemAttributesTransfer = $this->shoppingListItemsResourceMapper->mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
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

        return $shoppingListItemResource;
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
}
