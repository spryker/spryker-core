<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Controller;

use Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomerPasswordResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates customer password."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header",
     *              "description": "Locale value relevant for the store."
     *          }],
     *          "isEmptyResponse": true,
     *          "responses": {
     *              "400": "Passwords don't match.",
     *              "404": "Customer not found.",
     *              "406": "Invalid password."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $restRequest, RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createCustomerWriter()
            ->updateCustomerPassword($restRequest, $passwordAttributesTransfer);
    }
}
