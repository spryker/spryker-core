<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Controller;

use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CartReorderRestApi\CartReorderRestApiFactory getFactory()
 */
class CartReorderResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Makes cart reorder from existing order."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "orderReference",
     *                  "in": "query",
     *                  "required": true,
     *                  "description": "Order reference of existing order that should be amended."
     *              }
     *          ],
     *          "responses": {
     *              "400": "Order reference is missing.",
     *              "403": "Unauthorized cart action.",
     *              "404": "Order not found.",
     *              "422": "Errors appeared during cart reordering."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *          "isIdNullable": true
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createCartReorderCreator()
            ->reorder($restRequest, $restCartReorderRequestAttributesTransfer);
    }
}
