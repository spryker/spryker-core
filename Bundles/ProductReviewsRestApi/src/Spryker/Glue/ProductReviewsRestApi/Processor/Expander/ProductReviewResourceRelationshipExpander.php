<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface;

class ProductReviewResourceRelationshipExpander implements ProductReviewResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface
     */
    protected $productReviewReader;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface $productReviewReader
     */
    public function __construct(ProductReviewReaderInterface $productReviewReader)
    {
        $this->productReviewReader = $productReviewReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addRelationshipsByAbstractSku(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $this->addRelationship($resource->getId(), $restRequest, $resource);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addRelationshipsByConcreteSku(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $this->addRelationship(
                $resource->getAttributes()->offsetGet(ConcreteProductsRestAttributesTransfer::ID_PRODUCT_ABSTRACT),
                $restRequest,
                $resource
            );
        }

        return $resources;
    }

    /**
     * @param string $abstractSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addRelationship(
        string $abstractSku,
        RestRequestInterface $restRequest,
        RestResourceInterface $resource
    ): void {
        $productReviews = $this->productReviewReader->findProductReviewsByAbstractSku(
            $restRequest,
            $abstractSku,
            $restRequest->getMetadata()->getLocale()
        );
        foreach ($productReviews as $productReview) {
            $resource->addRelationship($productReview);
        }
    }
}
