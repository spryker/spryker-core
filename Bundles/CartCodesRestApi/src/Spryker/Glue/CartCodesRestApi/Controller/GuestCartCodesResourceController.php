<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Controller;

use Generated\Shared\Transfer\RestCartCodeRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CartCodesRestApi\CartCodesRestApiFactory getFactory()
 */
class GuestCartCodesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Adds a code to guest cart."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "404": "Cart with given uuid not found.",
     *              "422": "Cart code can't be added."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartCodeRequestAttributesTransfer $restCartCodeRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestCartCodeRequestAttributesTransfer $restCartCodeRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createCartCodeAdder()
            ->addCartCodeToGuestCart($restRequest, $restCartCodeRequestAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "path": "/guest-carts/{guestCartId}/cart-codes/{code}",
     *          "summary": [
     *              "Deletes a code from guest cart."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "404": "Cart with given uuid not found.",
     *              "422": "Cart code can't be removed."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAction(
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        return $this->getFactory()->createCartCodeRemover()->removeCartCodeFromGuestCart($restRequest);
    }
}
