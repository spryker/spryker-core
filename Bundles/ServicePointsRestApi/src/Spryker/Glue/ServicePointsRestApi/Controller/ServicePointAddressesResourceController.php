<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

/**
 * @method \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiFactory getFactory()
 */
class ServicePointAddressesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves service point address by uuid."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "Content-Type",
     *                  "in": "header",
     *                  "description": "Content-Type header required for all the requests."
     *              },
     *              {
     *                  "name": "Fields",
     *                  "in": "query",
     *                  "description": "Parameter is used to extract specified items fields."
     *              }
     *          ],
     *          "responses": {
     *              "400": "Bad Request",
     *              "404": "Not Found"
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
                ->createServicePointAddressReader()
                ->getServicePointAddress($restRequest);
        }

        return $this->getFactory()
            ->createErrorResponseBuilder()
            ->createErrorResponse(
                ServicePointsRestApiConfig::GLOSSARY_KEY_ERROR_ENDPOINT_NOT_FOUND,
                $restRequest->getMetadata()->getLocale(),
            );
    }
}
