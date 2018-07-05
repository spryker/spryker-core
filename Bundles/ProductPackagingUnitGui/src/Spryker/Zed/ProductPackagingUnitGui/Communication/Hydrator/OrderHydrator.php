<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Hydrator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductFacadeInterface;

class OrderHydrator implements OrderHydratorInterface
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected $salesOrderItemQuery;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery
     * @param \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductFacadeInterface $productFacade
     */
    public function __construct(
        SpySalesOrderItemQuery $salesOrderItemQuery,
        ProductPackagingUnitGuiToProductFacadeInterface $productFacade
    ) {
        $this->salesOrderItemQuery = $salesOrderItemQuery;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrder(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderItemEntity = $this->findByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());
            if (!$orderItemEntity || !$orderItemEntity->getAmountSku() || !$orderItemEntity->getAmount()) {
                continue;
            }

            $productConcreteTransfer = $this->findProductConcreteBySku($orderItemEntity->getAmountSku());
            if (!$productConcreteTransfer) {
                continue;
            }

            $itemTransfer->setAmountLeadProduct(
                $this->createProductPackagingLeadProductTransfer($productConcreteTransfer)
            );
        }

        return $orderTransfer;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null
     */
    protected function findByIdSalesOrderItem(int $idSalesOrderItem): ?SpySalesOrderItem
    {
        return $this->salesOrderItemQuery->findOneByIdSalesOrderItem($idSalesOrderItem);
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer
     */
    protected function createProductPackagingLeadProductTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductPackagingLeadProductTransfer
    {
        return (new ProductPackagingLeadProductTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());
    }
}
