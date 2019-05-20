<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Controller;

use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiFactory getFactory()
 */
class SharedCartsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Shares a cart."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Cart id is missing",
     *              "403": "Unauthorized request.",
     *              "404": "Cart or company user not found.",
     *              "422": "Failed to share a cart."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createSharedCartCreator()
            ->create($restRequest, $restSharedCartsAttributesTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates permission group for shared cart."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Shared cart id is missing.",
     *              "403": "Unauthorized request.",
     *              "404": "Shared cart not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $restRequest, RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createSharedCartUpdater()
            ->update($restRequest, $restSharedCartsAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Deletes cart sharing."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Shared cart id is missing.",
     *              "403": "Unauthorized request.",
     *              "404": "Shared cart not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createSharedCartDeleter()
            ->delete($restRequest);
    }
}
