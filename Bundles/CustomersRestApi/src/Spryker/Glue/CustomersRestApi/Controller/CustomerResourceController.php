<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Controller;

use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomerResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves customer data."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Customer id is not specified.",
     *              "403": "Unauthorized request.",
     *              "404": "Customer not found."
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
        return $this->getFactory()
            ->createCustomerReader()
            ->getCustomerByCustomerReference($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates customer."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Terms and Conditions was not accepted.",
     *              "422": "Customer with this email already exists."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $restCustomersAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRequestInterface $restRequest, RestCustomersAttributesTransfer $restCustomersAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createCustomerWriter()
            ->registerCustomer($restRequest, $restCustomersAttributesTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates customer data."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Failed to save customer.",
     *              "403": "Unauthorized request.",
     *              "404": "Customer not found."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $restRequest, RestCustomersAttributesTransfer $customerTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createCustomerWriter()
            ->updateCustomer($restRequest, $customerTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Anonymizes customers."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Customer id is not specified.",
     *              "403": "Unauthorized request.",
     *              "404": "Customer not found."
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
            ->createCustomerWriter()
            ->anonymizeCustomer($restRequest);
    }
}
