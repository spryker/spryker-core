<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Controller\BackendApi;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
class MultiFactorAuthBackendResourceController extends AbstractController
{
    /**
     * @Glue({
     *       "getCollection": {
     *            "summary": [
     *                "Retrieves multi-factor authentication types."
     *            ],
     *            "parameters": [
     *                {
     *                    "ref": "acceptLanguage"
     *                }
     *            ],
     *            "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestMultiFactorAuthAttributesTransfer",
     *            "responses": {
     *                "403": "Forbidden"
     *            }
     *       }
     *   })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createMultiFactorAuthBackendApiReader()
            ->getMultiFactorAuthTypes($glueRequestTransfer);
    }
}
