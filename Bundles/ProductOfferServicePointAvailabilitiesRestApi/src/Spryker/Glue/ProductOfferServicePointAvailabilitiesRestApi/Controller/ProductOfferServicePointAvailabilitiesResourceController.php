<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Controller;

use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\ProductOfferServicePointAvailabilitiesRestApiFactory getFactory()
 */
class ProductOfferServicePointAvailabilitiesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Returns list of Product Offer Service Point Availabilities filtered by criteria."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "Content-Type",
     *                  "in": "header",
     *                  "description": "Content-Type header required for all the requests."
     *              }
     *          ],
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer",
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer",
     *          "responses": {
     *              "200": "OK",
     *              "400": "Bad Request",
     *              "422": "Unprocessable entity."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer $restProductOfferServicePointAvailabilitiesRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer $restProductOfferServicePointAvailabilitiesRequestAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createProductOfferServicePointAvailabilityReader()
            ->getProductOfferServicePointAvailabilities(
                $restRequest,
                $restProductOfferServicePointAvailabilitiesRequestAttributesTransfer,
            );
    }
}
