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
class ConfiguredBundlesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *         "path": "/carts/{cartId}/configured-bundles",
     *         "summary": [
     *             "Adds a configured bundle to the cart."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }],
     *         "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *         "responses": {
     *             "400": "Cart id is missing.",
     *             "403": "Unauthorized cart action.",
     *             "404": "Cart not found.",
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
            ->createConfiguredBundleWriter()
            ->addConfiguredBundle($restRequest, $restConfiguredBundlesAttributesTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *         "path": "/carts/{cartId}/configured-bundles/{configuredBundleId}",
     *         "summary": [
     *             "Updates configured bundle quantity."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }],
     *         "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCartsAttributesTransfer",
     *         "responses": {
     *             "400": "Cart id or item id is not specified.",
     *             "403": "Unauthorized cart action.",
     *             "404": "Cart or item not found.",
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
            ->createConfiguredBundleWriter()
            ->updateConfiguredBundleQuantity($restRequest, $restConfiguredBundlesAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *         "path": "/carts/{cartId}/configured-bundles/{configuredBundleId}",
     *         "summary": [
     *             "Removes configured bundle from the cart."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }],
     *         "responses": {
     *             "400": "Cart id or item id is not specified.",
     *             "403": "Unauthorized cart action.",
     *             "404": "Cart or cart item not found.",
     *             "422": "Configured bundle could not be deleted."
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
            ->createConfiguredBundleWriter()
            ->deleteConfiguredBundle($restRequest);
    }
}
