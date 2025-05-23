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
class QuoteRequestAgentsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a quote request by reference."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "version",
     *                  "in": "query",
     *                  "required": false,
     *                  "description": "Version of the quote request."
     *              }
     *          ],
     *          "isIdNullable": false,
     *          "responses": {
     *              "404": "Quote request not found.",
     *              "403": "Unauthorized request.",
     *              "400": "Bad request."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer"
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves quote request list."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "403": "Unauthorized request."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer"
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
                ->createQuoteRequestReader()
                ->findQuoteRequest($restRequest);
        }

        return $this->getFactory()
            ->createQuoteRequestReader()
            ->getQuoteRequestCollectionByFilter($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a quote request as an agent."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "requestAttributesClassName": "\\Generated\\Shared\\Transfer\\RestAgentQuoteRequestsRequestAttributesTransfer",
     *          "attributes": {
     *              "companyUserUuid": {
     *                  "type": "string",
     *                  "required": true,
     *                  "description": "Company user UUID for whom the quote request is being created."
     *              }
     *          },
     *          "isIdNullable": true,
     *          "responses": {
     *              "400": "Bad request.",
     *              "403": "Unauthorized request.",
     *              "422": "Unprocessable entity."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer"
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

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates a quote request as an agent."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "requestAttributesClassName": "\\Generated\\Shared\\Transfer\\RestAgentQuoteRequestsRequestAttributesTransfer",
     *          "attributes": {
     *              "metadata": {
     *                  "type": "object",
     *                  "required": false,
     *                  "description": "Additional metadata for the quote request."
     *              },
     *              "validUntil": {
     *                  "type": "string",
     *                  "required": false,
     *                  "description": "Date until which the quote is valid."
     *              },
     *              "isLatestVersionVisible": {
     *                  "type": "boolean",
     *                  "required": false,
     *                  "description": "Indicates if the latest version should be visible to the customer."
     *              },
     *              "unitPriceMap": {
     *                  "type": "object",
     *                  "required": false,
     *                  "description": "Map of groupKeys to their unit prices."
     *              }
     *          },
     *          "isIdNullable": false,
     *          "responses": {
     *              "400": "Quote request id is missing.",
     *              "403": "Unauthorized request.",
     *              "404": "Quote request not found.",
     *              "422": "Unprocessable entity."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestQuoteRequestsAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createQuoteRequestUpdater()
            ->update($restRequest);
    }
}
