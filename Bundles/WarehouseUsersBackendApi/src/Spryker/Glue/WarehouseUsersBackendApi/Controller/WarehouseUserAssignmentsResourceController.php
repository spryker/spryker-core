<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Controller;

use Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiFactory getFactory()
 */
class WarehouseUserAssignmentsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves warehouse user assignments collection."
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
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiWarehouseUserAssignmentsAttributesTransfer",
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
            ->createWarehouseUserAssignmentReader()
            ->getWarehouseUserAssignmentCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves warehouse user assignment by uuid."
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
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiWarehouseUserAssignmentsAttributesTransfer",
     *          "responses": {
     *              "403": "Unauthorized request",
     *              "404": "Not found"
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
            ->createWarehouseUserAssignmentReader()
            ->getWarehouseUserAssignment($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates warehouse user assignment."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiWarehouseUserAssignmentsAttributesTransfer",
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiWarehouseUserAssignmentsAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createWarehouseUserAssignmentCreator()
            ->createWarehouseUserAssignment(
                $apiWarehouseUserAssignmentsAttributesTransfer,
                $glueRequestTransfer,
            );
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates a concrete warehouse user assignment."
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
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiWarehouseUserAssignmentsAttributesTransfer",
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiWarehouseUserAssignmentsAttributesTransfer",
     *          "responses": {
     *              "403": "Unauthorized request",
     *              "404": "Not Found"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(
        ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createWarehouseUserAssignmentUpdater()
            ->updateWarehouseUserAssignment(
                $apiWarehouseUserAssignmentsAttributesTransfer,
                $glueRequestTransfer,
            );
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Deletes a concrete warehouse user assignment."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiWarehouseUserAssignmentsAttributesTransfer",
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
    public function deleteAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createWarehouseUserAssignmentDeleter()
            ->deleteWarehouseUserAssignment($glueRequestTransfer);
    }
}
