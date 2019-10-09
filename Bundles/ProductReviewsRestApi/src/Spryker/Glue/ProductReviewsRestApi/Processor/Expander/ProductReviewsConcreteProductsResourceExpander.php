<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface;

class ProductReviewsConcreteProductsResourceExpander implements ProductReviewsConcreteProductsResourceExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface
     */
    protected $productReviewsStorageClient;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface $productReviewsStorageClient
     */
    public function __construct(ProductReviewsRestApiToProductReviewStorageClientInterface $productReviewsStorageClient)
    {
        $this->productReviewsStorageClient = $productReviewsStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    public function expand(ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer): ConcreteProductsRestAttributesTransfer
    {
        $productReviewsStorageTransfer = $this->productReviewsStorageClient
            ->findProductAbstractReview($concreteProductsRestAttributesTransfer->getIdProductAbstract());

        return $concreteProductsRestAttributesTransfer->fromArray($productReviewsStorageTransfer->toArray(), true);
    }
}
