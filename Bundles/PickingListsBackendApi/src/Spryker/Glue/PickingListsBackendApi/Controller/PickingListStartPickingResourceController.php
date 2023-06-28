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
class PickingListStartPickingResourceController extends AbstractBackendApiController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Assigns the warehouse user to the picking list and updates the picking list to indicate that picking has started."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responses": {
     *              "400": "Bad request",
     *              "403": "Unauthorized request.",
     *              "404": "Not found"
     *          },
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiPickingListsRequestAttributesTransfer",
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiPickingListsAttributesTransfer"
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createPickingListUpdater()
            ->startPicking($glueRequestTransfer);
    }
}
