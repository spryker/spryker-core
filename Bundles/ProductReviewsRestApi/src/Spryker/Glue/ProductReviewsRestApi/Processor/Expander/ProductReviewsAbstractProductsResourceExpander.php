<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface;

class ProductReviewsAbstractProductsResourceExpander implements ProductReviewsAbstractProductsResourceExpanderInterface
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
     * @param string $idProductAbstract
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function expand(
        string $idProductAbstract,
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
    ): AbstractProductsRestAttributesTransfer {
        $productReviewsStorageTransfer = $this->productReviewsStorageClient
            ->findProductAbstractReview($idProductAbstract);

        if (!$productReviewsStorageTransfer) {
            $abstractProductsRestAttributesTransfer->setReviewCount(0);

            return $abstractProductsRestAttributesTransfer;
        }

        return $abstractProductsRestAttributesTransfer->fromArray($productReviewsStorageTransfer->toArray(), true);
    }
}
