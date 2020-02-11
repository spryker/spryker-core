<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class AmountLeadProductHydrateOrder implements AmountLeadProductHydrateOrderInterface
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
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository,
        ProductPackagingUnitToProductFacadeInterface $productFacade
    ) {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAmountLeadProduct(OrderTransfer $orderTransfer): OrderTransfer
    {
        $spySalesOrderItemEntityTransfers = $this->productPackagingUnitRepository
            ->findSalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        foreach ($spySalesOrderItemEntityTransfers as $spySalesOrderItemEntityTransfer) {
            $itemTransfer = $this->findItemTransferAmountSalesUnitsBelongTo(
                $orderTransfer,
                $spySalesOrderItemEntityTransfer->getIdSalesOrderItem()
            );

            if (!$itemTransfer) {
                continue;
            }

            $this->setAmountLeadProduct($itemTransfer, $spySalesOrderItemEntityTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
     *
     * @return void
     */
    protected function setAmountLeadProduct(ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer): void
    {
        if (!$spySalesOrderItemEntityTransfer->getAmountSku() || !$spySalesOrderItemEntityTransfer->getAmount()) {
            return;
        }

        $leadProductConcreteTransfer = $this->findProductConcreteBySku($spySalesOrderItemEntityTransfer->getAmountSku());

        if (!$leadProductConcreteTransfer) {
            return;
        }

        $itemTransfer->setAmountLeadProduct($leadProductConcreteTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer
    {
        $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($sku);

        if (!$idProductConcrete) {
            return null;
        }

        return $this->productFacade->findProductConcreteById($idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransferAmountSalesUnitsBelongTo(OrderTransfer $orderTransfer, $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
