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
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $defaultProductImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function combineProductImageSets(array $localizedProductImageSetTransfers, array $defaultProductImageSetTransfers): array
    {
        $combinedProductImageSetTransfers = [];
        $mappedProductImageSetTransfers = $this->mapProductImageSetsByLocaleName($localizedProductImageSetTransfers);

        $mappedDefaultProductImageSetTransfers = $this->getProductImageSetsIndexedByName($defaultProductImageSetTransfers);

        foreach ($mappedProductImageSetTransfers as $productImageSetTransfers) {
            $mergedProductImageSetTransfersForLocale = array_merge(
                $mappedDefaultProductImageSetTransfers,
                $this->getProductImageSetsIndexedByName($productImageSetTransfers)
            );

            $combinedProductImageSetTransfers = $combinedProductImageSetTransfers + $this->getProductImageSetsIndexedById($mergedProductImageSetTransfersForLocale);
        }

        $combinedProductImageSetTransfers = count($combinedProductImageSetTransfers) ? $combinedProductImageSetTransfers : $defaultProductImageSetTransfers;

        return array_values($combinedProductImageSetTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[][]
     */
    protected function mapProductImageSetsByLocaleName(array $productImageSetTransfers): array
    {
        $localizedProductImageSetTransfers = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $localizedProductImageSetTransfers[$productImageSetTransfer->getLocale()->getLocaleName()][] = $productImageSetTransfer;
        }

        return $localizedProductImageSetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getProductImageSetsIndexedByName(array $productImageSetTransfers): array
    {
        $indexedProductImageSetTransfers = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $indexedProductImageSetTransfers[$productImageSetTransfer->getName()] = $productImageSetTransfer;
        }

        return $indexedProductImageSetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getProductImageSetsIndexedById(array $productImageSetTransfers): array
    {
        $indexedProductImageSetTransfers = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $indexedProductImageSetTransfers[$productImageSetTransfer->getIdProductImageSet()] = $productImageSetTransfer;
        }

        return $indexedProductImageSetTransfers;
    }
}
