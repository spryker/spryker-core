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
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header"
     *          }],
     *          "responseClass": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *          "responses": {
     *              "404": "Cart with given uuid not found.",
     *              "422": "Product \"{sku}\" not found"
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
            ->addItem($request, $restCartItemsAttributesTransfer);
    }

    /**
     * @Glue({
     *      "patch": {
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true
     *          }],
     *          "responseClass": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *          "responses": {
     *              "400": "Cart uuid or item group key is not specified.",
     *              "404": "Item with the given group key not found in the cart.",
     *              "422": "Product \"{sku}\" not found"
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
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true
     *          }],
     *          "responseClass": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *          "responses": {
     *              "400": "Cart uuid or item group key is not specified.",
     *              "404": "Item with the given group key not found in the cart.",
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
