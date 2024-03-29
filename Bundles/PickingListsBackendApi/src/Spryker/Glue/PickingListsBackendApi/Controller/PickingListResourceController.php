<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractBackendApiController;

/**
 * @method \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiFactory getFactory()
 */
class PickingListResourceController extends AbstractBackendApiController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves the picking list collection."
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
     *                  "ref": "Filter"
     *              },
     *              {
     *                  "ref": "Sort"
     *              }
     *          ],
     *          "responses": {
     *              "403": "Unauthorized request."
     *          },
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\PickingListsBackendApiAttributesTransfer"
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
            ->createPickingListReader()
            ->getPickingListCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves the picking list."
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
     *          "responses": {
     *              "403": "Unauthorized request.",
     *              "404": "Not found"
     *          },
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\PickingListsBackendApiAttributesTransfer"
     *     }
     * })
     *
     * @param string $uuid
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getAction(string $uuid, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createPickingListReader()
            ->getPickingList(
                $uuid,
                $glueRequestTransfer,
            );
    }
}
