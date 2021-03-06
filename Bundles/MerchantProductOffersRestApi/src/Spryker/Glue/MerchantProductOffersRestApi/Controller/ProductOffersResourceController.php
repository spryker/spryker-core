<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiFactory getFactory()
 */
class ProductOffersResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves a collection of product offers."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Product offer id is not specified.",
     *              "404": "Product offer not found."
     *          }
     *      }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createProductOfferReader()
            ->getProductOffer($restRequest);
    }
}
