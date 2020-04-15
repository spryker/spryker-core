<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Controller;

use Generated\Shared\Transfer\RestShoppingListRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiFactory getFactory()
 */
class ShoppingListsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a shopping list by id."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "403": "Unauthorized request.",
     *              "404": "Shopping list is not found."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestShoppingListsAttributesTransfer"
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves list of all customer's shopping lists."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "403": "Unauthorized request."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestShoppingListsAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idShoppingList = $restRequest->getResource()->getId();

        if ($idShoppingList !== null) {
            return $this->getFactory()->createShoppingListReader()->getCustomerShoppingList(
                $idShoppingList,
                $restRequest
            );
        }

        return $this->getFactory()
            ->createShoppingListReader()
            ->getCustomerShoppingListCollection($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a shopping list."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "403": "Unauthorized request.",
     *              "422": "Cannot create a shopping list."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestShoppingListsAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListRequestAttributesTransfer $restShoppingListRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestShoppingListRequestAttributesTransfer $restShoppingListRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createShoppingListCreator()
            ->createShoppingList($restRequest, $restShoppingListRequestAttributesTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates a shopping list by id."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Shopping list id not specified.",
     *              "403": "Unauthorized request.",
     *              "404": "Shopping list not found.",
     *              "422": "Cannot patch a shopping list."
     *          },
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\RestShoppingListsAttributesTransfer"
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListRequestAttributesTransfer $restShoppingListRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(
        RestRequestInterface $restRequest,
        RestShoppingListRequestAttributesTransfer $restShoppingListRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createShoppingListUpdater()
            ->updateShoppingList($restRequest, $restShoppingListRequestAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Deletes a shopping list by id."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Shopping list id not specified.",
     *              "403": "Unauthorized request.",
     *              "404": "Shopping list not found."
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
            ->createShoppingListDeleter()
            ->deleteShoppingList($restRequest);
    }
}
