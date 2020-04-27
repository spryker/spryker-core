<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Mapper;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class ProductLabelDictionaryItemMapper implements ProductLabelDictionaryItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    public function mapProductLabelTransfersToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
        array $productLabelTransfers
    ): array {
        $productLabelDictionaryItemTransfers = [];

        foreach ($productLabelTransfers as $productLabelTransfer) {
            foreach ($productLabelTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                $productLabelDictionaryItemTransfers = $this->mapProductLabelTransferToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
                    $productLabelTransfer,
                    $storeTransfer,
                    $productLabelDictionaryItemTransfers
                );
            }
        }

        return $productLabelDictionaryItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][] $productLabelDictionaryItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    protected function mapProductLabelTransferToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
        ProductLabelTransfer $productLabelTransfer,
        StoreTransfer $storeTransfer,
        array $productLabelDictionaryItemTransfers
    ): array {
        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $productLabelLocalizedAttributesTransfer) {
            $productLabelDictionaryItemTransfers[$storeTransfer->getName()][$productLabelLocalizedAttributesTransfer->getLocale()->getLocaleName()][] =
                (new ProductLabelDictionaryItemTransfer())
                    ->setName($productLabelLocalizedAttributesTransfer->getName())
                    ->setIdProductLabel($productLabelLocalizedAttributesTransfer->getFkProductLabel())
                    ->setKey($productLabelTransfer->getName())
                    ->setIsExclusive($productLabelTransfer->getIsExclusive())
                    ->setPosition($productLabelTransfer->getPosition())
                    ->setFrontEndReference($productLabelTransfer->getFrontEndReference());
        }

        return $productLabelDictionaryItemTransfers;
    }
}
