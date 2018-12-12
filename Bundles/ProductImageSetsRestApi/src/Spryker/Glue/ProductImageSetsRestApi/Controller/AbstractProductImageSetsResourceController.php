<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiFactory getFactory()
 */
class AbstractProductImageSetsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves abstract product image sets."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Abstract product id is not specified.",
     *              "404": "Abstract product image sets not found."
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
            ->createAbstractProductImageSetsReader()
            ->getAbstractProductImageSets($restRequest);
    }
}
