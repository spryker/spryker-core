<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CartPermissionGroupsRestApi\CartPermissionGroupsRestApiFactory getFactory()
 */
class CartPermissionGroupsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves cart permission group by id."
     *          ],
     *          "responses": {
     *              "404": "Cart permission group not found."
     *          }
     *     },
     *      "getCollection": {
     *          "summary": [
     *              "Retrieves collection of cart permission groups."
     *          ]
     *      }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->getFactory()
                ->createCartPermissionGroupReader()
                ->getCartPermissionGroupList();
        }

        return $this->getFactory()
            ->createCartPermissionGroupReader()
            ->findCartPermissionGroupById((int)$restRequest->getResource()->getId());
    }
}
