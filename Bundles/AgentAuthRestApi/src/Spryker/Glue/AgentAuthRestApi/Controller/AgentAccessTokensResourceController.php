<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Controller;

use Generated\Shared\Transfer\RestAgentAccessTokensRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiFactory getFactory()
 */
class AgentAccessTokensResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Refreshes agent's access token."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAgentAccessTokensAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "401": "Failed to authenticate user.",
     *              "422": "Unprocessable entity."
     *          },
     *          "isIdNullable": true
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestAgentAccessTokensRequestAttributesTransfer $restAgentAccessTokensRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestAgentAccessTokensRequestAttributesTransfer $restAgentAccessTokensRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createAgentAccessTokenCreator()
            ->createAccessToken($restRequest, $restAgentAccessTokensRequestAttributesTransfer);
    }
}
