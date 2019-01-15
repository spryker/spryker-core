<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Controller;

use Generated\Shared\Transfer\RestDummyEntityAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\DummyEntitiesRestApi\DummyEntitiesRestApiFactory getFactory()
 */
class DummyEntitiesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a dummy entity by id."
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "Accept-Language",
     *                  "in": "header"
     *              }
     *          ],
     *          "responses": {
     *              "404": "Dummy entity is not found."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves list of all dummy entities."
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "Accept-Language",
     *                  "in": "header"
     *              }
     *          ]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idDummyEntity = $restRequest->getResource()->getId();

        if ($idDummyEntity !== null) {
            return $this->getFactory()->createDummyEntityReader()->getDummyEntity(
                $idDummyEntity,
                $restRequest
            );
        }

        return $this->getFactory()
            ->createDummyEntityReader()
            ->getDummyEntityCollection($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a dummy entity."
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "Accept-Language",
     *                  "in": "header"
     *              }
     *          ],
     *          "responses": {
     *              "422": "Cannot create a dummy entity."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestDummyEntityAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createDummyEntityCreator()
            ->createDummyEntity($restRequest, $restDummyEntityAttributesTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates a dummy entity by id."
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "Accept-Language",
     *                  "in": "header"
     *              }
     *          ],
     *          "responses": {
     *              "400": "Dummy entity id not specified.",
     *              "404": "Dummy entity not found.",
     *              "422": "Cannot patch a dummy entity."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestDummyEntityAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(
        RestRequestInterface $restRequest,
        RestDummyEntityAttributesTransfer $restDummyEntityAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createDummyEntityUpdater()
            ->updateDummyEntity($restRequest, $restDummyEntityAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Deletes a dummy entity by id."
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "Accept-Language",
     *                  "in": "header"
     *              }
     *          ],
     *          "responses": {
     *              "400": "Dummy entity id not specified.",
     *              "404": "Dummy entity not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createDummyEntityDeleter()
            ->deleteDummyEntity($restRequest);
    }
}
