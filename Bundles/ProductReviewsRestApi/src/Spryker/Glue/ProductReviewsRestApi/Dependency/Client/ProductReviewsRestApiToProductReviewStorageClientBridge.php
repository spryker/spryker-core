<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Dependency\Client;

class ProductReviewsRestApiToProductReviewStorageClientBridge implements ProductReviewsRestApiToProductReviewStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductReviewStorage\ProductReviewStorageClientInterface
     */
    protected $productReviewsStorageClient;

    /**
     * @param \Spryker\Client\ProductReviewStorage\ProductReviewStorageClientInterface $productReviewsStorageClient
     */
    public function __construct($productReviewsStorageClient)
    {
        $this->productReviewsStorageClient = $productReviewsStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer
     */
    public function findProductAbstractReview($idProductAbstract)
    {
        return $this->productReviewsStorageClient->findProductAbstractReview($idProductAbstract);
    }
}
