<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Combiner;

class ProductImageSetCombiner implements ProductImageSetCombinerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $localizedProductImageSetTransfers
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function combineProductImageSets(array $localizedProductImageSetTransfers, array $productImageSetTransfers): array
    {
        return array_values($this->getProductImageSetsIndexedByName($localizedProductImageSetTransfers) + $this->getProductImageSetsIndexedByName($productImageSetTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSets
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getProductImageSetsIndexedByName(array $productImageSets): array
    {
        $indexedProductImageSets = [];

        foreach ($productImageSets as $productImageSet) {
            $indexedProductImageSets[$productImageSet->getName()] = $productImageSet;
        }

        return $indexedProductImageSets;
    }
}
