<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Controller;

use Generated\Shared\Transfer\RestPreOrderPaymentRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\PaymentsRestApi\PaymentsRestApiFactory getFactory()
 */
class PreOrderPaymentsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a pre-order payment and returns payment provider data that should be used on the store front payment page."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\PreOrderPaymentResponseTransfer",
     *          "responses": {
     *              "201": "Created.",
     *              "400": "Bad request.",
     *              "401": "Failed to authenticate user.",
     *              "422": "Unprocessable entity."
     *          },
     *          "isIdNullable": true
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestPreOrderPaymentRequestAttributesTransfer $restPreOrderPaymentRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestPreOrderPaymentRequestAttributesTransfer $restPreOrderPaymentRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createPayment()
            ->initializePreOrderPayment($restRequest, $restPreOrderPaymentRequestAttributesTransfer);
    }
}
