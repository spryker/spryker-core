<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

/**
 * @Glue({
 *     "resourceAttributesClassName": "\\Generated\\Shared\\Transfer\\RestShoppingListItemsAttributesTransfer"
 * })
 *
 * @method \Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiFactory getFactory()
 */
class ShoppingListItemByShoppingListResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `shopping-list-items` resource as relationship by shopping list.
     * - Requires ShoppingListTransfer be provided in resource payload.
     *
     * @api
     *
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createShoppingListItemExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS;
    }
}
