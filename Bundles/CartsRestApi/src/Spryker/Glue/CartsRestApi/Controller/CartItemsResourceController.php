<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Controller;

use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartItemsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Adds an item to the cart."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Cart id is missing.",
     *              "403": "Unauthorized cart action.",
     *              "404": "Cart not found.",
     *              "422": "Errors appeared during item creation."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createCartItemAdder()
            ->addItem(
                $restRequest,
                $restCartItemsAttributesTransfer
            );
    }

    /**
     * @Glue({
     *     "patch": {
     *          "path": "/carts/{cartId}/items/{itemId}",
     *          "summary": [
     *              "Updates cart item quantity."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Cart id or item id is not specified.",
     *              "403": "Unauthorized cart action.",
     *              "404": "Cart or item not found.",
     *              "422": "Errors appeared during item update."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface$restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $restRequest, RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createCartItemUpdater()
            ->updateItemQuantity(
                $restRequest,
                $restCartItemsAttributesTransfer
            );
    }

    /**
     * @Glue({
     *     "delete": {
     *          "path": "/carts/{cartId}/items/{itemId}",
     *          "summary": [
     *              "Removes item from the cart."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Cart id or item id is not specified.",
     *              "403": "Unauthorized cart action.",
     *              "404": "Cart or cart item not found.",
     *              "422": "Cart item could not be deleted."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createCartItemDeleter()
            ->deleteItem(
                $restRequest
            );
    }
}
