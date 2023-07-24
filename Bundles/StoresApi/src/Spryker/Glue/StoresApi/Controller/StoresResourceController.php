<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Controller\AbstractStorefrontApiController;

/**
 * @method \Spryker\Glue\StoresApi\StoresApiFactory getFactory()
 */
class StoresResourceController extends AbstractStorefrontApiController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves the store."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\StoresRestAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request",
     *              "404": "Store not found."
     *          }
     *     }
     * })
     *
     * @param string $id
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getAction(
        string $id,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createStoreReader()
            ->getStore($id, $glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves store collection."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\StoresRestAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "404": "Not Found."
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
            ->createStoreReader()
            ->getStoreCollection($glueRequestTransfer);
    }
}
