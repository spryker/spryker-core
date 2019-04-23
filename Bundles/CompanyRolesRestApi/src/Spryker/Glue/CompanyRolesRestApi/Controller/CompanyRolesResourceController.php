<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiFactory getFactory()
 */
class CompanyRolesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a company role by id."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Company role id is missing.",
     *              "404": "Company role not found."
     *          }
     *     },
     *    "getCollection": {
     *          "summary": [
     *              "Retrieves company role collection."
     *          ],
     *          "responses": {
     *              "501": "Not implemented."
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
        if (!$restRequest->getResource()->getId()) {
            return $this->getFactory()
                ->createCompanyRoleRestResponseBuilder()
                ->createResourceNotImplementedError();
        }

        return $this->getFactory()->createCompanyRoleReader()->getCurrentUserCompanyRole($restRequest);
    }
}
