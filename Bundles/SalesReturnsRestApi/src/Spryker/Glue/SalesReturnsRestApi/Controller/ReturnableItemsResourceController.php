<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Controller;

use Generated\Shared\Transfer\RestReturnableItemRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiFactory getFactory()
 */
class ReturnableItemsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves list of returnable items."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestReturnableItemRequestAttributesTransfer $restReturnableItemRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(
        RestRequestInterface $restRequest,
        RestReturnableItemRequestAttributesTransfer $restReturnableItemRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createReturnableItemReader()
            ->getReturnableItems($restRequest, $restReturnableItemRequestAttributesTransfer);
    }
}
