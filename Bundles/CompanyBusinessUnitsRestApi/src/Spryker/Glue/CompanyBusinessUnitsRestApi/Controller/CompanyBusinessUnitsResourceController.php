<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiFactory getFactory()
 */
class CompanyBusinessUnitsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a company business unit by id."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Company business unit id is missing.",
     *              "404": "Company business unit not found."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves company business units collection."
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
                ->createCompanyBusinessUnitRestResponseBuilder()
                ->createResourceNotImplementedError();
        }

        return $this->getFactory()->createCompanyBusinessUnitReader()->getCurrentUserCompanyBusinessUnit($restRequest);
    }
}
