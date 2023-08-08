<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiFactory getFactory()
 */
class ServicePointsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a service point by uuid."
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
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves service points collection."
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
     *              },
     *              {
     *                  "name": "Filter",
     *                  "in": "query",
     *                  "description": "The parameter is used to filter items by specified values."
     *              },
     *              {
     *                  "name": "Page",
     *                  "in": "query",
     *                  "description": "The parameter is used to control the pagination behavior of the result set of API response."
     *              },
     *              {
     *                  "name": "q",
     *                  "in": "query",
     *                  "description": "The parameter is used to represent a search query."
     *              },
     *              {
     *                  "name": "Sort",
     *                  "in": "query",
     *                  "description": "The parameter used to specify the sorting order of the result set of API response. To specify the sort direction, prepend a hyphen '-' in front of the field name, indicating descending order."
     *              }
     *          ],
     *          "responses": {
     *              "400": "Bad Request"
     *          }
     *      }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $servicePointReader = $this->getFactory()->createServicePointReader();

        if ($restRequest->getResource()->getId()) {
            return $servicePointReader->getServicePoint($restRequest);
        }

        return $servicePointReader->getServicePointCollection($restRequest);
    }
}
