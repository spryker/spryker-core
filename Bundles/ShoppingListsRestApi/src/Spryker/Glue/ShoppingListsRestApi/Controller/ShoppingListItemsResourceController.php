<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiFactory getFactory()
 */
class ShoppingListItemsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Adds shopping list item."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          },
     *          {
     *              "name": "X-Company-User-Id",
     *              "in": "header",
     *              "required": true,
     *              "description": "Company user id"
     *          }],
     *          "responses": {
     *              "400": "Shopping list id not specified."
     *              "403": "Unauthorized request.",
     *              "403": "Company user not found."
     *              "404": "Shopping list not found."
     *              "422": "Can't add an item to shopping list",
     *          },
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        return $this->getFactory()
            ->createShoppingListItemAdder()
            ->addItem($restRequest);
    }
}
