<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Controller;

use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
class MultiFactorAuthTypeVerifyResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Verifies a multi-factor authentication code for a specific type"
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "204": "No content.",
     *              "400": "Bad Request",
     *              "403": "Forbidden"
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createMultiFactorAuthTypeVerifyProcessor()
            ->verifyMultiFactorAuth($restRequest, $restMultiFactorAuthAttributesTransfer);
    }
}
