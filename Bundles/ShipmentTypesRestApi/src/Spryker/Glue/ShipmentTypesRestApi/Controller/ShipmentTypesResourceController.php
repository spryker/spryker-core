<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiFactory getFactory()
 */
class ShipmentTypesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a shipment type by uuid."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "fields",
     *                  "in": "query",
     *                  "description": "Parameter is used to extract specified shipment type fields."
     *              }
     *          ],
     *          "responses": {
     *              "400": "Bad Request",
     *              "404": "Not Found"
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves a shipment types collection."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "fields",
     *                  "in": "query",
     *                  "description": "Parameter is used to extract specified items fields."
     *              },
     *              {
     *                  "name": "sort",
     *                  "in": "query",
     *                  "description": "Parameter is used to sort items. Use dash `-` for DESC direction. Only sorting by key is supported.",
     *                  "schema": {
     *                      "type": "string",
     *                      "example": "-key"
     *                  }
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
        $shipmentTypeReader = $this->getFactory()->createShipmentTypeReader();

        if ($restRequest->getResource()->getId()) {
            return $shipmentTypeReader->getShipmentType($restRequest);
        }

        return $shipmentTypeReader->getShipmentTypeCollection($restRequest);
    }
}
