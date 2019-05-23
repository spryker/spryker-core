<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AlternativeProductsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\AlternativeProductsRestApi\AlternativeProductsRestApiFactory getFactory()
 */
class ConcreteAlternativeProductsController extends AbstractController
{
    /**
     * @Glue({
     *      "getCollection": {
     *          "summary": [
     *              "Retrieves concrete alternative products of concrete product."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Concrete product id is not specified.",
     *              "404": "Concrete product not found."
     *          }
     *      }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getFactory()
                ->createAlternativeProductRestResponseBuilder()
                ->createRouteNotFoundError();
        }

        return $this->getFactory()
            ->createConcreteAlternativeProductReader()
            ->getConcreteAlternativeProductCollection($restRequest);
    }
}
