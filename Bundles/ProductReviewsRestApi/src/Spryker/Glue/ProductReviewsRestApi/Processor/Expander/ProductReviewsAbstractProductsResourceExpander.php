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
    protected $productReviewStorageClient;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientInterface $productReviewStorageClient
     */
    public function __construct(ProductReviewsRestApiToProductReviewStorageClientInterface $productReviewStorageClient)
    {
        $this->productReviewStorageClient = $productReviewStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function expand(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer,
        int $idProductAbstract
    ): AbstractProductsRestAttributesTransfer {
        $productReviewStorageTransfer = $this->productReviewStorageClient->findProductAbstractReview($idProductAbstract);
        if (!$productReviewStorageTransfer) {
            $abstractProductsRestAttributesTransfer->setReviewCount(0);

            return $abstractProductsRestAttributesTransfer;
        }

        return $abstractProductsRestAttributesTransfer->fromArray($productReviewStorageTransfer->toArray(), true);
    }
}
