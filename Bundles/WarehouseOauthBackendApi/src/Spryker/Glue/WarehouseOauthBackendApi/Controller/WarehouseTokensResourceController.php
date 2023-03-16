<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractBackendApiController;

/**
 * @method \Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiFactory getFactory()
 */
class WarehouseTokensResourceController extends AbstractBackendApiController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates warehouse access tokens."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiTokenResponseAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiTokenAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request",
     *              "403": "Unauthorized request."
     *          }
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
            ->createWarehouseTokenCreator()
            ->createWarehouseToken($glueRequestTransfer);
    }
}
