<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListItemsResourcePlugin extends AbstractPlugin implements ResourceRoutePluginInterface, ResourceWithParentPluginInterface
{
    /**
     * {@inheritDoc}
     * - Configures available actions for shopping-list-items resource.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(
        ResourceRouteCollectionInterface $resourceRouteCollection
    ): ResourceRouteCollectionInterface {
        $resourceRouteCollection
            ->addPost(ShoppingListsRestApiConfig::ACTION_SHOPPING_LIST_ITEMS_POST)
            ->addPatch(ShoppingListsRestApiConfig::ACTION_SHOPPING_LIST_ITEMS_PATCH)
            ->addDelete(ShoppingListsRestApiConfig::ACTION_SHOPPING_LIST_ITEMS_DELETE);

        return $resourceRouteCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return ShoppingListsRestApiConfig::CONTROLLER_SHOPPING_LIST_ITEMS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestShoppingListItemsAttributesTransfer::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getParentResourceType(): string
    {
        return ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS;
    }
}
