<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Controller\StorefrontApi;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
class MultiFactorAuthTypeActivateStorefrontResourceController extends AbstractController
{
    /**
     * @Glue({
     *      "post": {
     *           "summary": [
     *               "Activates a new multi-factor authentication type for a customer"
     *           ],
     *           "parameters": [{
     *               "ref": "acceptLanguage"
     *           }],
     *           "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestMultiFactorAuthAttributesTransfer",
     *           "requestAttributesClassName": "Generated\\Shared\\Transfer\\RestMultiFactorAuthAttributesTransfer",
     *           "responses": {
     *               "204": "No content.",
     *               "400": "Bad Request",
     *               "403": "Forbidden."
     *           }
     *      }
     *  })
     *
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createMultiFactorAuthTypeStorefrontApiActivateProcessor()
            ->activateMultiFactorAuth($glueRequestTransfer, $restMultiFactorAuthAttributesTransfer);
    }
}
