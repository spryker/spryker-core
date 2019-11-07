<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\RestProductOptionsAttributesTransfer;

class ProductOptionMapper implements ProductOptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[]
     */
    public function mapProductAbstractOptionStorageTransferToRestProductOptionsAttributesTransfers(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer,
        array $translations
    ): array {
        $restProductOptionsAttributesTransfers = [];

        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
            foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValueStorageTransfer) {
                $restProductOptionsAttributesTransfers[] = (new RestProductOptionsAttributesTransfer())
                    ->setSku($productOptionValueStorageTransfer->getSku())
                    ->setOptionGroupName($translations[$productOptionGroupStorageTransfer->getName()])
                    ->setOptionName($translations[$productOptionValueStorageTransfer->getValue()])
                    ->setPrice($productOptionValueStorageTransfer->getPrice())
                    ->setCurrencyIsoCode($productOptionValueStorageTransfer->getCurrencyIsoCode());
            }
        }

        return $restProductOptionsAttributesTransfers;
    }
}
