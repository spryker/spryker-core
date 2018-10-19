<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Controller;

use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResource": {
     *          "summary": [
     *              "Retrieve a cart."
     *          ],
     *          "headers": [
     *              "Accept-Language"
     *          ],
     *          "responses": {
     *              "404": "Cart was not found."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieve list of all customers carts."
     *          ],
     *          "headers": [
     *              "Accept-Language"
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
        $idQuote = $restRequest->getResource()->getId();

        if ($idQuote !== null) {
            return $this->getFactory()->createCartsReader()->readByIdentifier($idQuote, $restRequest);
        }

        return $this->getFactory()->createCartsReader()->readCurrentCustomerCarts($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Create cart."
     *          ],
     *          "headers": [
     *              "Accept-Language"
     *          ],
     *          "responses": {
     *              "500": "Can not create a cart."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestCartsAttributesTransfer $restCartsAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()->createCartsWriter()->create($restRequest, $restCartsAttributesTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Delete cart by id."
     *          ],
     *          "headers": [
     *              "Accept-Language"
     *          ],
     *          "responses": {
     *              "500": "Can not delete a cart."
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
        return $this->getFactory()->createCartsWriter()->delete($restRequest);
    }
}
