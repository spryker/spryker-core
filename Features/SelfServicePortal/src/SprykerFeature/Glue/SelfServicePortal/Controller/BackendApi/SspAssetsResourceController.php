<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Controller\BackendApi;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \SprykerFeature\Glue\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspAssetsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves SSP assets collection."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              },
     *              {
     *                  "ref": "Page"
     *              },
     *              {
     *                  "ref": "Fields"
     *              },
     *              {
     *                  "ref": "Sort"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\SspAssetsBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createSspAssetsReader()
            ->getSspAssetCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves SSP asset by reference."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              },
     *              {
     *                  "ref": "Fields"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\SspAssetsBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request",
     *              "404": "Not Found"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createSspAssetsReader()
            ->getSspAsset($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates SSP asset."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\SspAssetsBackendApiAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\SspAssetsBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createSspAssetsCreator()
            ->createSspAsset($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates SSP asset."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\SspAssetsBackendApiAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\SspAssetsBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request",
     *              "404": "Not Found"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createSspAssetsUpdater()
            ->updateSspAsset($glueRequestTransfer);
    }
}
