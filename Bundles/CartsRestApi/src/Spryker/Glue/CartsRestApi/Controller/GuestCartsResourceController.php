<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class GuestCartsResourceController extends AbstractController
{
    /**
     * @Glue({
     *      "getResourceById": {
     *          "summary": [
     *              "Retrieves a guest cart by id."
     *          ],
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true
     *          }],
     *          "responses": {
     *              "404": "Cart not found."
     *          }
     *     },
     *     "getCollection": {
     *           "summary": [
     *              "Retrieves list of customer's guest carts."
     *          ],
     *          "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true
     *          }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idQuote = $restRequest->getResource()->getId();

        if ($idQuote !== null) {
            return $this->getFactory()->createGuestCartReader()->readByIdentifier($idQuote, $restRequest);
        }

        return $this->getFactory()->createGuestCartReader()->readCurrentCustomerCarts($restRequest);
    }
}
