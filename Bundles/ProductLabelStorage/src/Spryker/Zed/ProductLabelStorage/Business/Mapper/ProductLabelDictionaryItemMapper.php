<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Mapper;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;

class ProductLabelDictionaryItemMapper
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param array $productLabelDictionaryItemTransfers
     * @param string $storeName
     *
     * @return array
     */
    public function mapProductLabelTransferToProductLabelDictionaryItemTransfersForStoreByLocale(
        ProductLabelTransfer $productLabelTransfer,
        array $productLabelDictionaryItemTransfers,
        string $storeName
    ): array {
        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $offsetKey => $productLabelLocalizedAttributesTransfer) {
            $productLabelDictionaryItemTransfers[$storeName][$productLabelLocalizedAttributesTransfer->getLocale()->getLocaleName()][] =
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
