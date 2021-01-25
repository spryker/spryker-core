<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface;

class ShoppingListItemExpander implements ShoppingListItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface
     */
    protected $shoppingListItemRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
     */
    public function __construct(ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder)
    {
        $this->shoppingListItemRestResponseBuilder = $shoppingListItemRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\ShoppingListTransfer|null $shoppingListTransfer
             */
            $shoppingListTransfer = $resource->getPayload();
            if (!$shoppingListTransfer instanceof ShoppingListTransfer) {
                continue;
            }

            $shoppingListItemRestResources = $this->shoppingListItemRestResponseBuilder
                ->createShoppingListItemRestResourcesFromShoppingListTransfer($shoppingListTransfer);

            foreach ($shoppingListItemRestResources as $shoppingListItemRestResource) {
                $resource->addRelationship($shoppingListItemRestResource);
            }
        }
    }
}
