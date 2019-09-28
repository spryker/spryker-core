<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Controller;

use Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiFactory getFactory()
 */
class CompanyUserAccessTokensResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates access token for company user."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestCompanyUserAccessTokenResponseAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "401": "Failed to authenticate user.",
     *              "403": "Unauthorized request.",
     *              "422": "Unprocessable entity."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createCompanyUserAccessTokenReader()
            ->processAccessTokenRequest($restRequest, $restCompanyUserAccessTokensAttributesTransfer);
    }
}
