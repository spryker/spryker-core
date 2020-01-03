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
class GuestCartItemsResourceController extends AbstractController
{
    /**
     * @Glue({
     *      "post": {
     *          "summary": [
     *              "Adds an item to the guest cart."
     *          ],
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *          "responses": {
     *              "404": "Cart not found.",
     *              "422": "Product not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $request
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $request, RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createGuestCartItemAdder()
            ->addItemToGuestCart($request, $restCartItemsAttributesTransfer);
    }

    /**
     * @Glue({
     *      "patch": {
     *          "path": "/guest-carts/{guestCartId}/guest-cart-items/{guestCartItemId}",
     *          "summary": [
     *              "Updates guest cart item quantity."
     *          ],
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *          "responses": {
     *              "400": "Cart id or item id is not specified.",
     *              "404": "Item with the given id not found in the cart.",
     *              "422": "Product not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $request
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $request, RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createGuestCartItemUpdater()
            ->updateItemQuantity($request, $restCartItemsAttributesTransfer);
    }

    /**
     * @Glue({
     *      "delete": {
     *          "path": "/guest-carts/{guestCartId}/guest-cart-items/{guestCartItemId}",
     *          "summary": [
     *              "Removes item from guest cart."
     *          ],
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *          "responses": {
     *              "400": "Cart id or item id is not specified.",
     *              "404": "Item with the given id not found in the cart.",
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
            ->createGuestCartItemDeleter()
            ->deleteItem($restRequest);
    }
}
