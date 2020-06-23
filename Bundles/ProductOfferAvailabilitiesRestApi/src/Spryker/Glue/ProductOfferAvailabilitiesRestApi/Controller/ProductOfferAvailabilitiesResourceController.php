<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ProductOfferAvailabilitiesRestApi\ProductOfferAvailabilitiesRestApiFactory getFactory()
 */
class ProductOfferAvailabilitiesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves collection of product-offer-availabilities."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Product offer id is not specified."
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
            ->createProductOfferAvailabilityReader()
            ->getProductOfferAvailabilities($restRequest);
    }
}
