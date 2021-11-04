<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListItemRestResponseBuilder extends RestResponseBuilder implements ShoppingListItemRestResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemMapperInterface
     */
    protected $shoppingListItemMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemMapperInterface $shoppingListItemMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListItemMapperInterface $shoppingListItemMapper,
        ShoppingListsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        parent::__construct($restResourceBuilder, $glossaryStorageClient);

        $this->shoppingListItemMapper = $shoppingListItemMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function createShoppingListItemRestResourcesFromShoppingListTransfer(ShoppingListTransfer $shoppingListTransfer): array
    {
        $restResources = [];
        $shoppingListUuid = $shoppingListTransfer->getUuid();
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $restResources[] = $this->createShoppingListItemRestResource(
                $shoppingListItemTransfer,
                $shoppingListUuid,
            );
        }

        return $restResources;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param string $idShoppingList
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildShoppingListItemRestResponse(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        string $idShoppingList
    ): RestResponseInterface {
        return $this->createRestResponse()->addResource(
            $this->createShoppingListItemRestResource($shoppingListItemTransfer, $idShoppingList),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param string $idShoppingList
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createShoppingListItemRestResource(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        string $idShoppingList
    ): RestResourceInterface {
        $restShoppingListItemsAttributesTransfer = $this->shoppingListItemMapper->mapShoppingListItemTransferToRestShoppingListItemsAttributesTransfer(
            $shoppingListItemTransfer,
            new RestShoppingListItemsAttributesTransfer(),
        );

        $shoppingListItemResource = $this->restResourceBuilder->createRestResource(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $shoppingListItemTransfer->getUuid(),
            $restShoppingListItemsAttributesTransfer,
        );

        $shoppingListItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForShoppingListItem(
                $idShoppingList,
                $shoppingListItemTransfer->getUuid(),
            ),
        );

        return $shoppingListItemResource;
    }

    /**
     * @param string $idShoppingList
     * @param string $idShoppingListItem
     *
     * @return string
     */
    protected function createSelfLinkForShoppingListItem(string $idShoppingList, string $idShoppingListItem): string
    {
        return sprintf(
            static::FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            $idShoppingList,
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            $idShoppingListItem,
        );
    }
}
