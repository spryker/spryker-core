<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Sales;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductBundleGroupTransfer;
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
        $bundledProducts = $this->getBundledProducts($orderTransfer);
        $orderTransfer->setBundleItems(new ArrayObject($bundledProducts));

        $itemGroups = $this->getItemGroups($orderTransfer);
        $orderTransfer->setItemGroups(new ArrayObject($itemGroups));

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
            ->orderBySku()
            ->findByFkSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getBundledProducts(OrderTransfer $orderTransfer)
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

        return $bundledProducts;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleGroupTransfer[]
     */
    protected function getItemGroups(OrderTransfer $orderTransfer)
    {
        $bundleGroups = $this->getBundleGroups($orderTransfer);
        $singleItemGroups = $this->getSingleItemGroups($orderTransfer);

        return $this->mergeGroups($bundleGroups, $singleItemGroups);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleGroupTransfer[]
     */
    protected function getBundleGroups(OrderTransfer $orderTransfer)
    {
        $bundleItems = $orderTransfer->getBundleItems();
        $items = $orderTransfer->getItems();

        $result = [];

        foreach ($bundleItems as $bundleId => $bundleGroupItem) {
            $associatedItems = [];
            $productGroup = new ProductBundleGroupTransfer();
            $rowCount = 0;
            foreach ($items as $itemTransfer) {
                if ($itemTransfer->getRelatedBundleItemIdentifier() === $bundleGroupItem->getBundleItemIdentifier()) {
                    $associatedItems[] = $itemTransfer;
                    $rowCount += count($itemTransfer->getProductOptions()) + 1;
                }
            }

            $productGroup->setGroupImage($bundleGroupItem->getMetadata()->getImage());
            $productGroup->setBundleItem($bundleGroupItem);
            $productGroup->setGroupItems(new ArrayObject($associatedItems));
            $productGroup->setRowCount($rowCount);
            $productGroup->setIsBundle(true);

            $result[] = $productGroup;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleGroupTransfer[]
     */
    protected function getSingleItemGroups(OrderTransfer $orderTransfer)
    {
        $items = $orderTransfer->getItems();

        $singleItems = [];
        foreach ($items as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() === null) {
                $singleItems[] = $itemTransfer;
            }
        }

        $result = [];

        foreach ($singleItems as $singleItem) {
            $productGroup = new ProductBundleGroupTransfer();
            $productGroup->setGroupItems(new ArrayObject([$singleItem]));
            $productGroup->setIsBundle(false);

            $result[] = $productGroup;
        }

        return $result;
    }

    /**
     * @param array $bundleGroups
     * @param array $singleItemGroups
     *
     * @return array
     */
    protected function mergeGroups(array $bundleGroups, array $singleItemGroups)
    {
        foreach ($singleItemGroups as $singleItemGroup) {
            $bundleGroups[] = $singleItemGroup;
        }

        return $bundleGroups;
    }
}
