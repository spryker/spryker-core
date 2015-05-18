<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Shared\Library\TransferLoader;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Shared\Catalog\Code\ProductAttributeConstantInterface;

class DefaultOrderSplitBundleItemBuilder extends OrderItemBuilder implements ProductAttributeConstantInterface
{


    /**
     * @return string
     */
    public function getBundleType()
    {
        return \SprykerFeature\Zed\Catalog\Persistence\Propel\Map\PacCatalogProductBundleTableMap::COL_BUNDLE_TYPE_SPLITBUNDLE;
    }

    /**
     * @param OrderItem $transferItem
     * @param Order $transferOrder
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem
     */
    public function createOrderItemEntity(OrderItem $transferItem, Order $transferOrder)
    {
        $sku = $transferItem->getSku();
        $bundleProducts = $this->facadeCatalog->getBundleProductsBySku($sku);

        if (!$bundleProducts) {
            throw new \LogicException('Not able to find products for requested SplitBundle: ' . $sku);
        }

        $recalculationOrder = $this->createRecalculationOrderTransfer($transferItem, $transferOrder, $bundleProducts);
        $this->distributePricing($transferItem, $recalculationOrder);
        $otherNonBundlesItems = $this->extractOtherNonBundleItemCopies($transferItem, $transferOrder);
        $this->appendItemsToOrder($otherNonBundlesItems, $recalculationOrder);
        $this->recalculateOrder($recalculationOrder);

        $collection = new \Propel\Runtime\Collection\Collection();
        $orderBundleEntity = $this->createItemBundleEntity($transferItem);

        foreach ($recalculationOrder->getItems() as $recalculationOrderItem) {
            if (!$this->isItemInList($recalculationOrderItem, $otherNonBundlesItems)) {
                $createdItem = parent::createOrderItemEntity($recalculationOrderItem, $recalculationOrder);
                $createdItem->setSalesOrderItemBundle($orderBundleEntity);
                $this->createItemBundleItem($createdItem, $orderBundleEntity);
                $collection->append($createdItem);
            }
        }

        return $collection;
    }

    /**
     * @param $item
     * @param $itemList
     * @return bool
     */
    protected function isItemInList($item, $itemList)
    {
        foreach ($itemList as $listItem) {
            if ($item === $listItem) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $items
     * @param Order $transferOrder
     */
    protected function appendItemsToOrder(array $items, Order $transferOrder)
    {
        foreach($items as $item) {
            $transferOrder->addItem($item);
        }
    }

    /**
     * @param OrderItem $transferItem
     * @param Order $transferOrder
     * @return void
     */
    protected function distributePricing(OrderItem $transferItem, Order $transferOrder)
    {
        $this->getDistributor()->distribute($transferItem, $transferOrder);
    }

    /**
     * @return Bundle\SplitBundleDistributor
     */
    protected function getDistributor()
    {
        return $this->factory->createModelBundleSplitBundleDistributor();
    }

    /**
     * @param Order $transferOrder
     */
    protected function recalculateOrder(Order $transferOrder)
    {
        $this->facadeCalculation->performSoftRecalculation($transferOrder);
    }

    /**
     * @param Order $transferOrder
     * @return Order
     */
    protected function createRecalculationOrderTransfer(OrderItem $transferItem, Order $transferOrder, $bundleProducts)
    {
        $order = clone $transferOrder;

        $itemCollection = $this->createRecalculationItemTransferCollection($transferItem, $bundleProducts);
        $order->setItems($itemCollection);

        $emptyTotals = new \Generated\Shared\Transfer\SalesPriceTotalsTransfer();
        $order->setTotals($emptyTotals);

        return $order;
    }

    /**
     * @param OrderItem $transferItem
     * @param Order $transferOrder
     * @return array
     */
    protected function extractOtherNonBundleItemCopies(OrderItem $transferItem, Order $transferOrder)
    {
        $otherItems = [];
        $splitBundleSku = $transferItem->getSku();
        foreach($transferOrder->getItems() as $item) {
            if($item->getSku() != $splitBundleSku) {
                $otherItems[] = clone $item;
            }
        }
        return $otherItems;
    }

    /**
     * @param \SprykerFeature\Zed\Catalog\Persistence\Propel\PacCatalogProductBundleProduct $bundleProducts
     * @return \SprykerFeature\Shared\Sales\Transfer\OrderItemCollection
     */
    protected function createRecalculationItemTransferCollection(OrderItem $transferItem, $bundleProducts)
    {
        $itemCollection = new OrderItemsTransfer();
        /* @var $bundleProduct \SprykerFeature\Zed\Catalog\Persistence\Propel\PacCatalogProductBundleProduct */
        foreach($bundleProducts as $bundleProduct) {
            $productEntity = $bundleProduct->getBundleProductProduct();
            $productComposite = $this->facadeCatalog->getProductByEntity($productEntity);
            $item = $this->createRecalculationItemTransfer($transferItem, $productComposite);
            $itemCollection->addOrderItem($item);
        }
        return $itemCollection;
    }

    /**
     * @param ReadOnlyProductComposite $productComposite
     * @return OrderItem
     */
    protected function createRecalculationItemTransfer(OrderItem $bundleTransferItem, ReadOnlyProductComposite $productComposite)
    {
        $item = new \Generated\Shared\Transfer\OrderItemTransfer();
        $grossPrice = $this->facadeCatalog->getProductPriceBySku($productComposite->getSku());

        $item->setSku($productComposite->getSku());
        $item->setVariety($productComposite->getVariety());
        $item->setName($productComposite[self::ATTRIBUTE_NAME]);
        $item->setTaxPercentage($productComposite[self::ATTRIBUTE_TAX_RATE]);
        $item->setGrossPrice($grossPrice);

        $this->createRecalculationItemOptionsTransfer($bundleTransferItem, $item);
        $this->createRecalculationItemExpensesTransfer($bundleTransferItem, $item);
        $this->createRecalculationItemDiscountsTransfer($bundleTransferItem, $item);

        return $item;
    }

    /**
     * @param OrderItem $transferItem
     * @return void
     */
    protected function createRecalculationItemOptionsTransfer(OrderItem $bundleTransferItem, OrderItem $splittedTransferItem)
    {
        $bundleItemOptions = clone $bundleTransferItem->getOptions();
        $splittedTransferItem->setOptions($bundleItemOptions);
    }

    /**
     * @param OrderItem $transferItem
     * @return void
     */
    protected function createRecalculationItemExpensesTransfer(OrderItem $bundleTransferItem, OrderItem $splittedTransferItem)
    {
        $bundleItemExpenses = clone $bundleTransferItem->getExpenses();
        $splittedTransferItem->setExpenses($bundleItemExpenses);
    }

    /**
     * @param OrderItem $transferItem
     * @return void
     */
    protected function createRecalculationItemDiscountsTransfer(OrderItem $bundleTransferItem, OrderItem $splittedTransferItem)
    {
        $bundlItemDiscounts = clone $bundleTransferItem->getDiscounts();
        $splittedTransferItem->setDiscounts($bundlItemDiscounts);
    }

    /**
     * @param OrderItem $transferItem
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle
     */
    protected function createItemBundleEntity(OrderItem $transferItem)
    {
        $bundleEntity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle();
        $bundleEntity->fromArray($transferItem->toArray());
        $bundleEntity->setBundleType($this->getBundleType());
        return $bundleEntity;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundleItem $bundle
     * @return mixed
     */
    protected function createItemBundleItem(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $itemEntity, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle $bundleEntity)
    {
        $sku = $itemEntity->getSku();
        $productComposite = $this->facadeCatalog->getProductBySku($sku);
        $grossPrice = $this->facadeCatalog->getProductPriceBySku($sku);
        $itemBundleItemEntity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundleItem();
        $itemBundleItemEntity->setVariety($productComposite->getVariety());
        $itemBundleItemEntity->setGrossPrice($grossPrice);
        $itemBundleItemEntity->setTaxPercentage($productComposite[self::ATTRIBUTE_TAX_RATE]);
        $itemBundleItemEntity->setName($productComposite[self::ATTRIBUTE_NAME]);
        $itemBundleItemEntity->setSku($productComposite->getSku());
        $itemBundleItemEntity->setSalesOrderItemBundle($bundleEntity);
    }

}
