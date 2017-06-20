<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Sales;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface;

class ProductBundlesSalesOrderHydrate implements ProductBundlesSalesOrderHydrateInterface
{

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface
     */
    protected $productBundlePriceCalculation;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface $salesQueryContainer
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface $productBundlePriceCalculation
     */
    public function __construct(
        ProductBundleToSalesQueryContainerInterface $salesQueryContainer,
        ProductBundlePriceCalculationInterface $productBundlePriceCalculation
    ) {
        $this->salesQueryContainer = $salesQueryContainer;
        $this->productBundlePriceCalculation = $productBundlePriceCalculation;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        $orderItems = $this->findOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        $bundledProducts = [];
        foreach ($orderItems as $salesOrderItemEntity) {
            if (!$salesOrderItemEntity->getFkSalesOrderItemBundle()) {
                continue;
            }

            $bundledProducts = $this->productBundlePriceCalculation->calculateForBundleItems(
                $orderTransfer,
                $salesOrderItemEntity,
                $bundledProducts
            );
        }

        $orderTransfer->setBundleItems(new ArrayObject($bundledProducts));

        return $orderTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findOrderItemsByIdSalesOrder($idSalesOrder)
    {
        return $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findByFkSalesOrder($idSalesOrder);
    }

}
