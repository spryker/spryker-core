<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Controller;

use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiFactory getFactory()
 */
class GuestConfiguredBundlesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *         "path": "/guest-carts/{guestCartId}/guest-configured-bundles/{configuredBundleId}",
     *         "summary": [
     *             "Adds a configured bundle to the guest cart."
     *         ],
     *         "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true,
     *              "description": "Guest customer unique ID."
     *         }],
     *         "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *         "responses": {
     *             "422": "The quantity of the configured bundle should be more than zero.",
     *             "422": "Configurable bundle template not found.",
     *             "403": "Unauthorized cart action.",
     *             "422": "Errors appeared during configured bundle creation."
     *         }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createGuestConfiguredBundleWriter()
            ->addConfiguredBundle($restRequest, $restConfiguredBundlesAttributesTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *         "path": "/guest-carts/{guestCartId}/guest-configured-bundles/{configuredBundleId}",
     *         "summary": [
     *             "Updates configured bundle quantity from the guest cart."
     *         ],
     *         "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true,
     *              "description": "Guest customer unique ID."
     *         }],
     *         "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *         "responses": {
     *             "400": "Cart id is missing.",
     *             "422": "The quantity of the configured bundle should be more than zero.",
     *             "403": "Unauthorized cart action.",
     *             "400": "Configured bundle with provided group key not found in cart.",
     *             "422": "Errors appeared during configured bundle update."
     *         }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(
        RestRequestInterface $restRequest,
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createGuestConfiguredBundleWriter()
            ->updateConfiguredBundleQuantity($restRequest, $restConfiguredBundlesAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *         "path": "/guest-carts/{guestCartId}/guest-configured-bundles/{configuredBundleId}",
     *         "summary": [
     *             "Removes configured bundle from the guest cart."
     *         ],
     *         "parameters": [{
     *              "name": "X-Anonymous-Customer-Unique-Id",
     *              "in": "header",
     *              "required": true,
     *              "description": "Guest customer unique ID."
     *         }],
     *         "responses": {
     *             "400": "Cart id is missing.",
     *             "403": "Unauthorized cart action.",
     *             "400": "Configured bundle with provided group key not found in cart.",
     *             "422": "Errors appeared during configured bundle deletion."
     *         }
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
            ->createGuestConfiguredBundleWriter()
            ->deleteConfiguredBundle($restRequest);
    }
}
