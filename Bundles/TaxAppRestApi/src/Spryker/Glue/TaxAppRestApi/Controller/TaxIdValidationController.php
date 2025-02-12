<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxAppRestApi\Controller;

use Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\TaxAppRestApi\TaxAppRestApiFactory getFactory()
 */
class TaxIdValidationController extends AbstractController
{
    /**
     * @Glue({
     *     "validateTaxId": {
     *          "summary": [
     *              "Validates taxId for country code."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "200": "Tax id is valid.",
     *              "400": "Validation is failed.",
     *              "422": "Tax identifier or country code is not specified"
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()->createTaxIdValidator()->validate($restTaxAppValidationAttributesTransfer);
    }
}
