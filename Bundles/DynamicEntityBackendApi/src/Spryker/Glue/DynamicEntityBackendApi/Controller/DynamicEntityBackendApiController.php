<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiFactory getFactory()
 */
class DynamicEntityBackendApiController extends AbstractController
{
    /**
     * @Glue({
     *      "getCollection": {
     *          "summary": [
     *              "Retrieves collection of dynamic entities."
     *          ]
     *      }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()->createDynamicEntityReader()->getDynamicEntityCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *      "get": {
     *          "summary": [
     *              "Retrieves a dynamic entity by ID."
     *          ]
     *      }
     * })
     *
     * @param string $id
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getAction(
        string $id,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createDynamicEntityReader()->getDynamicEntity($id, $glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates dynamic entities."
     *          ],
     *          "responses": {
     *              "400: "Bad request.",
     *              "404": "Dynamic entity configuration not found."
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createDynamicEntityCreator()->createDynamicEntityCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Update dynamic entity by Id or collection of entities."
     *          ],
     *          "responses": {
     *              "400: "Bad request.",
     *              "404": "Dynamic entity configuration not found."
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createDynamicEntityUpdater()->updateDynamicEntityCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Update dynamic entity by Id or collection of entities or creates it/them if it/they are not existed."
     *          ],
     *          "responses": {
     *              "400: "Bad request.",
     *              "404": "Dynamic entity configuration not found."
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function putAction(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createDynamicEntityUpdater()->updateDynamicEntityCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Delete dynamic entity by ID or criteria."
     *          ],
     *          "responses": {
     *              "204: "No Content.",
     *              "400: "Bad request.",
     *              "405": "Method not allowed."
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function deleteAction(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createDynamicEntityDeleter()->deleteDynamicEntity($glueRequestTransfer);
    }
}
