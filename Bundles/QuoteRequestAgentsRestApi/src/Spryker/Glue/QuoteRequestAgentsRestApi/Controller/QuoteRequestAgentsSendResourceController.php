<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiFactory getFactory()
 */
class QuoteRequestAgentsSendResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Sends the quote request to customer."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "requestAttributesClassName": "\\Generated\\Shared\\Transfer\\RestAgentQuoteRequestSendAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request",
     *              "403": "Unauthorized request.",
     *              "422": "Unprocessable entity."
     *          },
     *           "isIdNullable": false,
     *           "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createQuoteRequestSender()
            ->sendQuoteRequest($restRequest);
    }
}
