<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Grouper;

use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;

class ProductBundleGrouper implements ProductBundleGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer[]
     */
    public function groupProductForBundleTransfersByProductBundleTransfers(array $productForBundleTransfers): array
    {
        $productBundleTransfersGroupedByIdProductBundle = [];

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $idProductBundle = $productForBundleTransfer->getIdProductBundle();

            if (isset($productBundleTransfersGroupedByIdProductBundle[$idProductBundle])) {
                $productBundleTransfersGroupedByIdProductBundle[$idProductBundle] = $this->updateProductBundleTransfer(
                    $productBundleTransfersGroupedByIdProductBundle[$idProductBundle],
                    $productForBundleTransfer
                );

                continue;
            }

            $productBundleTransfersGroupedByIdProductBundle[$idProductBundle] = $this->createProductBundleTransfer($productForBundleTransfer);
        }

        return $productBundleTransfersGroupedByIdProductBundle;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer
     */
    protected function updateProductBundleTransfer(ProductBundleTransfer $productBundleTransfer, ProductForBundleTransfer $productForBundleTransfer): ProductBundleTransfer
    {
        return $productBundleTransfer
            ->addBundledProduct($productForBundleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer
     */
    protected function createProductBundleTransfer(ProductForBundleTransfer $productForBundleTransfer): ProductBundleTransfer
    {
        return (new ProductBundleTransfer())
            ->setIdProductConcrete($productForBundleTransfer->getIdProductBundle())
            ->addBundledProduct($productForBundleTransfer);
    }
}
