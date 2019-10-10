<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Creator;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;

class ProductReviewCreator implements ProductReviewCreatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productReviewClient = $productReviewClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestProductReviewsAttributesTransfer $restProductReviewAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReview(
        RestRequestInterface $restRequest,
        RestProductReviewsAttributesTransfer $restProductReviewAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $productReviewResponseTransfer = $this->productReviewClient->submitCustomerReview(
            (new ProductReviewRequestTransfer())->fromArray($restProductReviewAttributesTransfer->toArray())
                ->setLocaleName($restRequest->getMetadata()->getLocale())
                ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
        );

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductReviewsRestApiConfig::RESOURCE_PRODUCT_REVIEWS,
            $productReviewResponseTransfer->getProductReview()->getIdProductReview(),
            $restProductReviewAttributesTransfer
        );

        return $restResponse->addResource($restResource);
    }
}
