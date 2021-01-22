<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiFactory getFactory()
 */
class QuoteRequestsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a quote request by reference."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer",
     *          "responses": {
     *              "404": "Quote request not found.",
     *              "403": "Unauthorized request.",
     *              "400": "Bad request."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves quote request list."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer",
     *          "responses": {
     *              "404": "Quote request not found.",
     *              "403": "Unauthorized request.",
     *              "400": "Bad request."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getFactory()
                ->createQuoteRequestsReader()
                ->getQuoteRequest($restRequest);
        }

        return $this->getFactory()
            ->createQuoteRequestsReader()
            ->getQuoteRequestCollection($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a quote request as a company user."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request",
     *              "403": "Unauthorized request.",
     *              "422": "Unprocessable entity."
     *          }
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
            ->createQuoteRequestCreator()
            ->createQuoteRequest($restRequest);
    }
}
