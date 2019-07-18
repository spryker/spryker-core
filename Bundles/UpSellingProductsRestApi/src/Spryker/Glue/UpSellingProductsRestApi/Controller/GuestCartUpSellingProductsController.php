<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\UpSellingProductsRestApi\UpSellingProductsRestApiFactory getFactory()
 */
class GuestCartUpSellingProductsController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves list of all up-selling products for the guest cart."
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "Accept-Language",
     *                  "in": "header"
     *              },
     *              {
     *                  "name": "X-Anonymous-Customer-Unique-Id",
     *                  "in": "header",
     *                  "required": true
     *              }
     *          ],
     *          "responses": {
     *              "400": "Cart id is missing.",
     *              "404": "Cart with given id not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createUpSellingProductReader()
            ->readUpSellingProducts($restRequest);
    }
}
