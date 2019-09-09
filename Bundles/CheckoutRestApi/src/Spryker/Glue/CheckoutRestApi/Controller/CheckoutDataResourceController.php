<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Controller;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CheckoutRestApi\CheckoutRestApiFactory getFactory()
 */
class CheckoutDataResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Provides checkout data",
     *              " - available addresses",
     *              " - shipment methods",
     *              " - payment methods"
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "Accept-Language",
     *                  "in": "header"
     *              },
     *              {
     *                  "name": "X-Anonymous-Customer-Unique-Id",
     *                  "in": "header",
     *                  "required": false,
     *                  "description": "Guest customer unique ID"
     *              }
     *          ],
     *          "responses": {
     *              "200": "OK.",
     *              "400": "Bad Response.",
     *              "422": "Unprocessable entity."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestCheckoutDataResponseAttributesTransfer",
     *          "isIdNullable": true
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()->createCheckoutDataReader()->getCheckoutData($restRequest, $restCheckoutRequestAttributesTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()->getResourceBuilder()->createRestResponse();
    }
}
