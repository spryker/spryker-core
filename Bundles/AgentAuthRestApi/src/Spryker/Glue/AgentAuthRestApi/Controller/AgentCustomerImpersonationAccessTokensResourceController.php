<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Controller;

use Generated\Shared\Transfer\RestAgentCustomerImpersonationAccessTokensRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiFactory getFactory()
 */
class AgentCustomerImpersonationAccessTokensResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates customer imprsonation access token."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAgentCustomerImpersonationAccessTokensAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "401": "Action is available to agent user only.",
     *              "422": "Unprocessable entity."
     *          },
     *          "isIdNullable": true
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestAgentCustomerImpersonationAccessTokensRequestAttributesTransfer $restAgentCustomerImpersonationAccessTokensRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestAgentCustomerImpersonationAccessTokensRequestAttributesTransfer $restAgentCustomerImpersonationAccessTokensRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createAgentCustomerImpersonationAccessTokenCreator()
            ->create($restRequest, $restAgentCustomerImpersonationAccessTokensRequestAttributesTransfer);
    }
}
