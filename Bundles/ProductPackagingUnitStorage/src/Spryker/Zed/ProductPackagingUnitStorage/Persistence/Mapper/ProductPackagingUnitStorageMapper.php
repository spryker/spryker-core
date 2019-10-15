<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence\Mapper;

use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Spryker\DecimalObject\Decimal;

class ProductPackagingUnitStorageMapper implements ProductPackagingUnitStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer
     */
    public function mapProductConcretePackagingStorageEntityTransferToStorageTransfer(
        SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer,
        ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
    ): ProductConcretePackagingStorageTransfer {
        return $productConcretePackagingStorageTransfer
            ->fromArray($productPackagingUnitEntityTransfer->toArray(), true)
            ->setIdLeadProduct($productPackagingUnitEntityTransfer->getLeadProduct()->getIdProduct())
            ->setIdProduct($productPackagingUnitEntityTransfer->getFkProduct())
            ->setAmountInterval($this->trimDecimalValue($productPackagingUnitEntityTransfer->getAmountInterval()))
            ->setDefaultAmount($this->trimDecimalValue($productPackagingUnitEntityTransfer->getDefaultAmount()))
            ->setAmountMin($this->trimDecimalValue($productPackagingUnitEntityTransfer->getAmountMin()))
            ->setAmountMax($this->trimDecimalValue($productPackagingUnitEntityTransfer->getAmountMax()))
            ->setTypeName($productPackagingUnitEntityTransfer->getProductPackagingUnitType()->getName());
    }

    /**
     * @param \Spryker\DecimalObject\Decimal|null $decimalValue
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    protected function trimDecimalValue(?Decimal $decimalValue): ?Decimal
    {
        if ($decimalValue === null) {
            return $decimalValue;
        }

        return $decimalValue->trim();
    }
}
