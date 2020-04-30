<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Controller;

use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiFactory getFactory()
 */
class ProductReviewsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves abstract product review by sku."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "501": "Resource is not available."
     *          }
     *     },
     *      "getCollection": {
     *          "summary": [
     *              "Retrieves a collection of abstract product reviews by sku."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "400": "Abstract product id is not specified.",
     *              "404": "Abstract product not found."
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
        return $this->getFactory()->createProductReviewReader()->getProductReviews($restRequest);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a product review."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "202": "Accepted.",
     *              "400": "Abstract product id is not specified.",
     *              "401": "Invalid access token.",
     *              "403": "Missing access token.",
     *              "404": "Abstract product not found.",
     *              "422": "Unprocessable entity."
     *          }
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequestInterface $restRequest,
        RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer
    ): RestResponseInterface {
        return $this->getFactory()
            ->createProductReviewCreator()
            ->createProductReview($restRequest, $restProductReviewsAttributesTransfer);
    }
}
