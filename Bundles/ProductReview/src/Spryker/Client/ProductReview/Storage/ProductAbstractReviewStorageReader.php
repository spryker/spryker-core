<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Storage;

use Generated\Shared\Transfer\ProductAbstractReviewTransfer;
use Spryker\Client\ProductReview\Dependency\Client\ProductReviewToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductAbstractReviewStorageReader implements ProductAbstractReviewStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Client\ProductReview\Dependency\Client\ProductReviewToStorageInterface $storageClient
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(
        ProductReviewToStorageInterface $storageClient,
        KeyBuilderInterface $keyBuilder
    ) {
        $this->storageClient = $storageClient;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractReviewTransfer|null
     */
    public function findProductAbstractReview($idProductAbstract, $localeName)
    {
        $key = $this->keyBuilder->generateKey($idProductAbstract, $localeName);
        $productAbstractReviewData = $this->storageClient->get($key);

        if (!$productAbstractReviewData) {
            return null;
        }

        return $this->mapProductAbstractReviewData($productAbstractReviewData);
    }

    /**
     * @param array $productAbstractReviewData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractReviewTransfer
     */
    protected function mapProductAbstractReviewData(array $productAbstractReviewData)
    {
        $productAbstractReviewTransfer = new ProductAbstractReviewTransfer();
        $productAbstractReviewTransfer->fromArray($productAbstractReviewData, true);

        return $productAbstractReviewTransfer;
    }
}
