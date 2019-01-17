<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Builder;

use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Builder\RestResponseBuilder;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListItemRestResponseBuilder extends RestResponseBuilder implements ShoppingListItemRestResponseBuilderInterface
{
    protected const FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface
     */
    protected $shoppingListItemMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface $shoppingListItemMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListItemMapperInterface $shoppingListItemMapper
    ) {
        parent::__construct($restResourceBuilder);

        $this->shoppingListItemMapper = $shoppingListItemMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createShoppingListItemRestResourcesFromShoppingListTransfer(
        ShoppingListTransfer $shoppingListTransfer
    ): array {
        $restResources = [];
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $restResources[] = $this->createShoppingListItemRestResource(
                $shoppingListItemTransfer,
                $shoppingListTransfer->getUuid()
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
            $this->createShoppingListItemRestResource($shoppingListItemTransfer, $idShoppingList)
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
        $restShoppingListItemAttributesTransfer = $this->shoppingListItemMapper->mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
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
