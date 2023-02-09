<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Business\Allocator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductWarehouseCriteriaTransfer;
use Spryker\Zed\ProductWarehouseAllocationExample\Persistence\ProductWarehouseAllocationExampleRepositoryInterface;

class SalesOrderWarehouseAllocator implements SalesOrderWarehouseAllocatorInterface
{
    /**
     * @var \Spryker\Zed\ProductWarehouseAllocationExample\Persistence\ProductWarehouseAllocationExampleRepositoryInterface
     */
    protected ProductWarehouseAllocationExampleRepositoryInterface $productWarehouseAllocationExampleRepository;

    /**
     * @param \Spryker\Zed\ProductWarehouseAllocationExample\Persistence\ProductWarehouseAllocationExampleRepositoryInterface $productWarehouseAllocationExampleRepository
     */
    public function __construct(ProductWarehouseAllocationExampleRepositoryInterface $productWarehouseAllocationExampleRepository)
    {
        $this->productWarehouseAllocationExampleRepository = $productWarehouseAllocationExampleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocate(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->allocateSalesOrderItemWarehouse($itemTransfer, $orderTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function allocateSalesOrderItemWarehouse(
        ItemTransfer $itemTransfer,
        OrderTransfer $orderTransfer
    ): ItemTransfer {
        $stockTransfer = $itemTransfer->getWarehouse();

        if ($stockTransfer && $stockTransfer->getIdStock()) {
            return $itemTransfer;
        }

        $productWarehouseCriteriaTransfer = $this->createProductWarehouseCriteriaTransfer(
            $itemTransfer,
            $orderTransfer,
        );

        $stockTransfer = $this->productWarehouseAllocationExampleRepository
            ->findProductWarehouse($productWarehouseCriteriaTransfer);

        if (!$stockTransfer) {
            return $itemTransfer;
        }

        $itemTransfer->setWarehouse($stockTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductWarehouseCriteriaTransfer
     */
    protected function createProductWarehouseCriteriaTransfer(
        ItemTransfer $itemTransfer,
        OrderTransfer $orderTransfer
    ): ProductWarehouseCriteriaTransfer {
        return (new ProductWarehouseCriteriaTransfer())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setStoreName($orderTransfer->getStoreOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail());
    }
}
