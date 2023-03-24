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
class PickingListItemResourceController extends AbstractBackendApiController
{
    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates `PickingListItems.numberOfPicked` and `PickingListItems.numberOfNotPicked`."
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
     *              "400": "Bad request",
     *              "403": "Unauthorized request.",
     *              "404": "Not found"
     *          },
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiPickingListItemsAttributesTransfer",
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiPickingListsAttributesTransfer"
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createPickingListItemUpdater()
            ->updatePickingListItems($glueRequestTransfer);
    }
}
