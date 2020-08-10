<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Controller;

use Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CartCodesRestApi\CartCodesRestApiFactory getFactory()
 */
class CartVouchersResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Adds a code to cart."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "401": "Invalid access token.",
     *              "403": "Missing access token.",
     *              "404": "Cart with given uuid not found.",
     *              "422": "Cart code can't be added."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createCartCodeAdder()
            ->addDiscountCodeToCart($restRequest, $restDiscountRequestAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "path": "/carts/{cartId}/vouchers/{voucherCode}",
     *          "summary": [
     *              "Deletes a code from cart."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "401": "Invalid access token.",
     *              "403": "Missing access token.",
     *              "404": "Cart with given uuid not found.",
     *              "422": "Cart code not found in cart."
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
        return $this->getFactory()->createCartCodeRemover()->removeDiscountCodeFromCart($restRequest);
    }
}
