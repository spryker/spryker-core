<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Controller;

use Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomerForgottenPasswordResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Create customer forgotten password."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header",
     *              "description": "Locale value relevant for the store."
     *          }],
     *          "responses": {
     *              "204": "No content."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createCustomerForgottenPasswordProcessor()
            ->sendPasswordRestoreMail($restCustomerForgottenPasswordAttributesTransfer);
    }
}
