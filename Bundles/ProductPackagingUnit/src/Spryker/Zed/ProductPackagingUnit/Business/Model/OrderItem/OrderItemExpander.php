<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository,
        ProductPackagingUnitToProductFacadeInterface $productFacade,
        ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
    ) {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
        $this->productFacade = $productFacade;
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandSalesOrderItemWithAmountSalesUnit(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer {
        if (!$itemTransfer->getAmountSalesUnit()) {
            return $salesOrderItemEntity;
        }

        $amountBaseMeasurementUnitName = $itemTransfer->getAmountSalesUnit()
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $amountMeasurementUnitName = $itemTransfer->getAmountSalesUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $amountMeasurementUnitCode = $itemTransfer->getAmountSalesUnit()
            ->getProductMeasurementUnit()
            ->getCode();

        $salesOrderItemEntity->setAmountBaseMeasurementUnitName($amountBaseMeasurementUnitName);
        $salesOrderItemEntity->setAmountMeasurementUnitName($amountMeasurementUnitName);
        $salesOrderItemEntity->setAmountMeasurementUnitCode($amountMeasurementUnitCode);

        $salesOrderItemEntity->setAmountMeasurementUnitPrecision($itemTransfer->getAmountSalesUnit()->getPrecision());
        $salesOrderItemEntity->setAmountMeasurementUnitConversion($itemTransfer->getAmountSalesUnit()->getConversion());

        return $salesOrderItemEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandSalesOrderItemWithAmountAndAmountSku(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer {
        if (!$itemTransfer->getAmountLeadProduct()) {
            return $salesOrderItemEntity;
        }

        $packagingUnitLeadProductSku = $itemTransfer->getAmountLeadProduct()->getSku();
        $packagingUnitAmount = $itemTransfer->getAmount();

        $salesOrderItemEntity->setAmount($packagingUnitAmount);
        $salesOrderItemEntity->setAmountSku($packagingUnitLeadProductSku);

        return $salesOrderItemEntity;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithAmountSalesUnit(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $mappedProductMeasurementSalesUnitTransfers = $this->productPackagingUnitRepository->getMappedProductMeasurementSalesUnits($salesOrderItemIds);

        $mappedProductMeasurementSalesUnitTransfers = $this->translateProductMeasurementSalesUnits($mappedProductMeasurementSalesUnitTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $productMeasurementSalesUnitTransfer = $mappedProductMeasurementSalesUnitTransfers[$itemTransfer->getIdSalesOrderItem()] ?? null;

            if ($productMeasurementSalesUnitTransfer) {
                $itemTransfer->setAmountSalesUnit($productMeasurementSalesUnitTransfer);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithAmountLeadProduct(array $itemTransfers): array
    {
        $mappedProductConcreteTransfers = $this->getMappedLeadProductsFromOrderItems($itemTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteTransfer = $mappedProductConcreteTransfers[$itemTransfer->getIdSalesOrderItem()] ?? null;

            if ($productConcreteTransfer) {
                $itemTransfer->setAmountLeadProduct($productConcreteTransfer);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer> $productMeasurementSalesUnitTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    protected function translateProductMeasurementSalesUnits(array $productMeasurementSalesUnitTransfers): array
    {
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            $this->productMeasurementUnitFacade->translateProductMeasurementSalesUnit($productMeasurementSalesUnitTransfer);
        }

        return $productMeasurementSalesUnitTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getMappedLeadProductsFromOrderItems(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $mappedLeadProductSkus = $this->productPackagingUnitRepository
            ->getMappedLeadProductSkusBySalesOrderItemIds($salesOrderItemIds);

        $productConcreteTransfers = $this->productFacade->findProductConcretesBySkus(array_unique($mappedLeadProductSkus));

        return $this->mapProductConcreteTransfersByIdSalesOrderItem($productConcreteTransfers, $mappedLeadProductSkus);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<string> $mappedLeadProductSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function mapProductConcreteTransfersByIdSalesOrderItem(
        array $productConcreteTransfers,
        array $mappedLeadProductSkus
    ): array {
        $mappedProductConcreteTransfers = [];
        $indexedProductConcreteTransfers = $this->indexProductConcreteTransfersBySku($productConcreteTransfers);

        foreach ($mappedLeadProductSkus as $idSalesOrderItem => $leadProductSku) {
            $productConcreteTransfer = $indexedProductConcreteTransfers[$leadProductSku] ?? null;

            if ($productConcreteTransfer) {
                $mappedProductConcreteTransfers[$idSalesOrderItem] = $productConcreteTransfer;
            }
        }

        return $mappedProductConcreteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function indexProductConcreteTransfersBySku(array $productConcreteTransfers): array
    {
        $indexedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $indexedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $indexedProductConcreteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int>
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return array_unique($salesOrderItemIds);
    }
}
