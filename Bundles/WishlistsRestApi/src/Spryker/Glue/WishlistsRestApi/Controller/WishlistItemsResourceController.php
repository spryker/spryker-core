<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Controller;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\WishlistsRestApi\WishlistsRestApiFactory getFactory()
 */
class WishlistItemsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "delete": {
     *          "path": "/wishlists/{wishlistId}/wishlist-items/{wishlistItemId}",
     *          "summary": [
     *              "Removes item from the wishlist."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "404": "Wishlist not found.",
     *              "422": "Wishlist item not found."
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
            ->createWishlistItemDeleter()
            ->delete($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Adds an item to the wishlist."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Can't add an item.",
     *              "404": "Wishlist not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createWishlistItemAdder()
            ->add($restWishlistItemsAttributesTransfer, $restRequest);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "path": "/wishlists/{wishlistId}/wishlist-items/{wishlistItemId}",
     *          "summary": [
     *              "Updates the wishlist item."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "403": "Unauthorized request.",
     *              "404": "Wishlist not found.",
     *              "422": "Cannot update the wishlist list item."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestWishlistItemsAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(
        RestRequestInterface $restRequest,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createWishlistItemUpdater()
            ->update($restRequest, $restWishlistItemsAttributesTransfer);
    }
}
