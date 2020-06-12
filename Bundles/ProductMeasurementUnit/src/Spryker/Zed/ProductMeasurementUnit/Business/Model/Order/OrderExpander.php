<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Order;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface
     */
    protected $productMeasurementUnitTranslationExpander;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface $productMeasurementUnitTranslationExpander
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitTranslationExpanderInterface $productMeasurementUnitTranslationExpander
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->productMeasurementUnitTranslationExpander = $productMeasurementUnitTranslationExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithQuantitySalesUnit(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderItemEntityTransfers = $this->productMeasurementUnitRepository
            ->querySalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        foreach ($salesOrderItemEntityTransfers as $salesOrderItemEntityTransfer) {
            $itemTransfer = $this->findItemTransferByIdSalesOrderItem(
                $orderTransfer,
                $salesOrderItemEntityTransfer->getIdSalesOrderItem()
            );

            if ($itemTransfer === null || $salesOrderItemEntityTransfer->getQuantityMeasurementUnitName() === null) {
                continue;
            }

            $itemTransfer->setQuantitySalesUnit(
                $this->hydrateQuantitySalesUnitTransfer($salesOrderItemEntityTransfer)
            );
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function hydrateQuantitySalesUnitTransfer(SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer): ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setConversion($salesOrderItemEntityTransfer->getQuantityMeasurementUnitConversion());
        $productMeasurementSalesUnitTransfer->setPrecision($salesOrderItemEntityTransfer->getQuantityMeasurementUnitPrecision());

        $productMeasurementBaseUnitTransfer = $this->createProductMeasurementBaseUnitTransfer($salesOrderItemEntityTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer(
            $salesOrderItemEntityTransfer->getQuantityMeasurementUnitName(),
            $salesOrderItemEntityTransfer->getQuantityMeasurementUnitCode()
        );
        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $this->productMeasurementUnitTranslationExpander->translateProductMeasurementSalesUnit($productMeasurementSalesUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param string|null $productMeasurementUnitName
     * @param string|null $productMeasurementUnitCode
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    protected function createProductMeasurementUnitTransfer(
        ?string $productMeasurementUnitName,
        ?string $productMeasurementUnitCode = null
    ): ProductMeasurementUnitTransfer {
        $productMeasurementUnitTransfer = new ProductMeasurementUnitTransfer();
        $productMeasurementUnitTransfer->setName($productMeasurementUnitName ?: '');

        if ($productMeasurementUnitCode !== null) {
            $productMeasurementUnitTransfer->setCode($productMeasurementUnitCode);
        }

        return $productMeasurementUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    protected function createProductMeasurementBaseUnitTransfer(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
    ): ProductMeasurementBaseUnitTransfer {
        $productMeasurementBaseUnitTransfer = new ProductMeasurementBaseUnitTransfer();

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($salesOrderItemEntityTransfer->getQuantityBaseMeasurementUnitName());
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransferByIdSalesOrderItem(OrderTransfer $orderTransfer, int $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
