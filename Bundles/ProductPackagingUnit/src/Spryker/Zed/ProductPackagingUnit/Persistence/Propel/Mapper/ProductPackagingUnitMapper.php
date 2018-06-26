<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProduct;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType;

class ProductPackagingUnitMapper implements ProductPackagingUnitMapperInterface
{
    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit $productPackagingUnitEntity
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTransfer $productPackagingUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer
     */
    public function mapProductPackagingUnitTransfer(
        SpyProductPackagingUnit $productPackagingUnitEntity,
        ProductPackagingUnitTransfer $productPackagingUnitTransfer
    ): ProductPackagingUnitTransfer {
        $productPackagingUnitAmountEntity = $productPackagingUnitEntity->getSpyProductPackagingUnitAmounts()->getFirst();

        $productPackagingUnitTransfer->fromArray($productPackagingUnitEntity->toArray(), true);

        if ($productPackagingUnitAmountEntity) {
            $productPackagingUnitTransfer->setProductPackagingUnitAmount(
                (new ProductPackagingUnitAmountTransfer())->fromArray($productPackagingUnitAmountEntity->toArray(), true)
            );
        }

        $productPackagingUnitTransfer->setProductPackagingUnitType(
            $this->mapProductPackagingUnitTypeTransfer(
                $productPackagingUnitEntity->getProductPackagingUnitType(),
                new ProductPackagingUnitTypeTransfer()
            )
        );

        return $productPackagingUnitTransfer;
    }

    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType $spyProductPackagingUnitType
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function mapProductPackagingUnitTypeTransfer(
        SpyProductPackagingUnitType $spyProductPackagingUnitType,
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->fromArray($spyProductPackagingUnitType->toArray(), true);

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProduct $productPackagingLeadProductEntity
     * @param \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer
     */
    public function mapProductPackagingLeadProductTransfer(
        SpyProductPackagingLeadProduct $productPackagingLeadProductEntity,
        ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer
    ): ProductPackagingLeadProductTransfer {
        $productPackagingLeadProductTransfer->setIdProduct($productPackagingLeadProductEntity->getFkProduct());
        $productPackagingLeadProductTransfer->setIdProductAbstract($productPackagingLeadProductEntity->getFkProductAbstract());
        $productPackagingLeadProductTransfer->setSkuProduct($productPackagingLeadProductEntity->getSpyProduct()->getSku());

        return $productPackagingLeadProductTransfer;
    }
}
