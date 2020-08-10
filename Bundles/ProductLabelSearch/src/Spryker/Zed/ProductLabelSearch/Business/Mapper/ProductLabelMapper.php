<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\Mapper;

use Generated\Shared\Transfer\ProductLabelTransfer;

class ProductLabelMapper implements ProductLabelMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransfers
     *
     * @return int[][]
     */
    public function getProductLabelIdsMappedByIdProductAbstractAndStoreName(array $productLabelTransfers): array
    {
        $productLabelIdsMap = [];

        foreach ($productLabelTransfers as $productLabelTransfer) {
            $productLabelIdsMap = $this->mapProductLabelTransferToProductLabelIdsByIdProductAbstractAndStoreName(
                $productLabelTransfer,
                $productLabelIdsMap
            );
        }

        return $productLabelIdsMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param int[][] $productLabelIdsMap
     *
     * @return int[][]
     */
    protected function mapProductLabelTransferToProductLabelIdsByIdProductAbstractAndStoreName(
        ProductLabelTransfer $productLabelTransfer,
        array $productLabelIdsMap
    ): array {
        foreach ($productLabelTransfer->getStoreRelation()->getStores() as $storeTransfer) {
            foreach ($productLabelTransfer->getProductLabelProductAbstracts() as $productLabelProductAbstract) {
                $productLabelIdsMap[$productLabelProductAbstract->getFkProductAbstract()][$storeTransfer->getName()][] = $productLabelTransfer->getIdProductLabel();
            }
        }

        return $productLabelIdsMap;
    }
}
