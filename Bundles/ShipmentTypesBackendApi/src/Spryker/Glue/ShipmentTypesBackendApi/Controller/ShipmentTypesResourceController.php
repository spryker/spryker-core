<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiFactory getFactory()
 */
class ShipmentTypesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves shipment types collection."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              },
     *              {
     *                  "ref": "Page"
     *              },
     *              {
     *                  "ref": "Fields"
     *              },
     *              {
     *                  "ref": "Sort"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ShipmentTypesBackendApiAttributesTransfer",
     *          "responses": {
     *              "403": "Unauthorized request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createShipmentTypeReader()
            ->getShipmentTypeCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves shipment type by uuid."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              },
     *              {
     *                  "ref": "Fields"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ShipmentTypesBackendApiAttributesTransfer",
     *          "responses": {
     *              "403": "Unauthorized request",
     *              "404": "Not Found"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createShipmentTypeReader()
            ->getShipmentType($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates shipment type."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ShipmentTypesBackendApiAttributesTransfer",
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ShipmentTypesBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createShipmentTypeCreator()
            ->createShipmentType($shipmentTypesBackendApiAttributesTransfer, $glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates shipment type."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              },
     *              {
     *                  "ref": "Fields"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ShipmentTypesBackendApiAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ShipmentTypesBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request",
     *              "404": "Not Found"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createShipmentTypeUpdater()
            ->updateShipmentType($shipmentTypesBackendApiAttributesTransfer, $glueRequestTransfer);
    }
}
