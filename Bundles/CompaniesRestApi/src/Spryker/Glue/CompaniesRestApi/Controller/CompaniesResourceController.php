<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Controller;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\CompaniesRestApi\CompaniesRestApiFactory getFactory()
 */
class CompaniesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a company by id."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Company id is missing.",
     *              "404": "Company not found."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves company collection."
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
        return $this->getFactory()->createCompanyReader()->getCompany($restRequest);
    }
}
