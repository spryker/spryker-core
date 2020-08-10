<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage\Storage;

use Generated\Shared\Transfer\ProductReviewStorageTransfer;
use Spryker\Client\ProductReviewStorage\Dependency\Client\ProductReviewStorageToStorageInterface;
use Spryker\Shared\ProductReviewStorage\ProductReviewStorageConfig;

class ProductAbstractReviewStorageReader implements ProductAbstractReviewStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductReviewStorage\Dependency\Client\ProductReviewStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductReviewStorage\Storage\ProductReviewStorageKeyGeneratorInterface
     */
    protected $productReviewStorageKeyGenerator;

    /**
     * @param \Spryker\Client\ProductReviewStorage\Dependency\Client\ProductReviewStorageToStorageInterface $storageClient
     * @param \Spryker\Client\ProductReviewStorage\Storage\ProductReviewStorageKeyGeneratorInterface $productReviewStorageKeyGenerator
     */
    public function __construct(
        ProductReviewStorageToStorageInterface $storageClient,
        ProductReviewStorageKeyGeneratorInterface $productReviewStorageKeyGenerator
    ) {
        $this->storageClient = $storageClient;
        $this->productReviewStorageKeyGenerator = $productReviewStorageKeyGenerator;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer|null
     */
    public function findProductAbstractReview($idProductAbstract)
    {
        $key = $this->productReviewStorageKeyGenerator->generateKey(ProductReviewStorageConfig::PRODUCT_ABSTRACT_REVIEW_RESOURCE_NAME, $idProductAbstract);

        return $this->findProductReviewProductStorageTransfer($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer|null
     */
    protected function findProductReviewProductStorageTransfer($key)
    {
        $imageData = $this->storageClient->get($key);

        if (!$imageData) {
            return null;
        }

        $ProductReviewStorageTransfer = new ProductReviewStorageTransfer();
        $ProductReviewStorageTransfer->fromArray($imageData, true);

        return $ProductReviewStorageTransfer;
    }
}
