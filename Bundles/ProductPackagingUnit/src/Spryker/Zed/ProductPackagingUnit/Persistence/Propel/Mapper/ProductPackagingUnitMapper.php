<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProduct;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

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
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->fromArray($productPackagingLeadProductEntity->getSpyProduct()->toArray(), true);
        $productPackagingLeadProductTransfer->setProduct($productConcreteTransfer);
        $productPackagingLeadProductTransfer->setIdProductAbstract($productPackagingLeadProductEntity->getFkProductAbstract());

        return $productPackagingLeadProductTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function mapSpySalesOrderItemEntityTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        $spySalesOrderItemEntityTransfer->fromArray($salesOrderItemEntity->toArray(), true);

        return $spySalesOrderItemEntityTransfer;
    }

    /**
     * @param array $orderItemEntityArray
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function mapOrderItemEntityArrayToItemTransfer(array $orderItemEntityArray, ItemTransfer $itemTransfer): ItemTransfer
    {
        return $itemTransfer
            ->setProcess($orderItemEntityArray[SpyOmsOrderProcessTableMap::COL_NAME])
            ->setState((new ItemStateTransfer())->setName($orderItemEntityArray[SpyOmsOrderItemStateTableMap::COL_NAME]))
            ->setSku($orderItemEntityArray[SpySalesOrderItemTableMap::COL_SKU])
            ->setQuantity($orderItemEntityArray[SpySalesOrderItemTableMap::COL_QUANTITY]);
    }
}
