<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Controller\StorefrontApi\RestApi;

use Generated\Shared\Transfer\RestSspAssetsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \SprykerFeature\Glue\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspAssetsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *         "summary": [
     *             "Retrieves asset data by id."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }],
     *         "responses": {
     *             "404": "Asset not found.",
     *             "500": "Unexpected error."
     *         }
     *     },
     *     "getCollection": {
     *         "summary": [
     *             "Retrieves all assets for the authenticated company user."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $resourceId = $restRequest->getResource()->getId();
        $assetsReader = $this->getFactory()->createAssetsReader();

        if ($resourceId !== null) {
            return $assetsReader->getSspAsset($restRequest);
        }

        return $assetsReader->getSspAssets($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *         "summary": [
     *             "Creates an asset."
     *         ],
     *         "parameters": [{
     *             "ref": "acceptLanguage"
     *         }],
     *         "responses": {
     *             "409": "Asset integrity error.",
     *             "422": "Unprocessable asset.",
     *             "500": "Unexpected error."
     *         }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestSspAssetsAttributesTransfer $restSspAssetsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestSspAssetsAttributesTransfer $restSspAssetsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createAssetsCreator()
            ->create($restRequest, $restSspAssetsAttributesTransfer);
    }
}
