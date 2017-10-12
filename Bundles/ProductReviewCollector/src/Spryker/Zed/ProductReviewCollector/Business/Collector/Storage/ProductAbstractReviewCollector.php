<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductAbstractReviewTransfer;
use Spryker\Shared\ProductReview\ProductReviewConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class ProductAbstractReviewCollector extends AbstractStoragePropelCollector
{
    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductReviewConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_REVIEW;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $productAbstractReviewTransfer = $this->mapDataToTransfer($collectItemData);

        return $productAbstractReviewTransfer->modifiedToArray();
    }

    /**
     * @param array $collectItemData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractReviewTransfer
     */
    protected function mapDataToTransfer(array $collectItemData)
    {
        $productAbstractReviewTransfer = new ProductAbstractReviewTransfer();
        $productAbstractReviewTransfer
            ->fromArray($collectItemData, true)
            ->setAverageRating(round($collectItemData[ProductAbstractReviewTransfer::AVERAGE_RATING], 1));

        return $productAbstractReviewTransfer;
    }
}
