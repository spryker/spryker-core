<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiFactory getFactory()
 */
class AbstractProductsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves product abstarct list by the content abstract product list content item."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Content key is missing.",
     *              "404": "Content item not found.",
     *              "422": "Content type is invalid."
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
            ->createProductAbstractReader()
            ->getProductAbstractByContentProductAbstractListId($restRequest);
    }
}
