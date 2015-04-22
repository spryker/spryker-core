<?php
namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Catalog\Code\ProductAttributeConstantInterface;

class DefaultOrderNonSplitBundleItemBuilder extends OrderItemBuilder implements ProductAttributeConstantInterface
{

    /**
     * @return string
     */
    public function getBundleType()
    {
        return \SprykerFeature\Zed\Catalog\Persistence\Propel\Map\PacCatalogProductBundleTableMap::COL_BUNDLE_TYPE_NONSPLITBUNDLE;
    }

    /**
     * @param OrderItem $transferItem
     * @param Order $transferOrder
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem
     */
    public function createOrderItemEntity(OrderItem $transferItem, Order $transferOrder)
    {
        $itemEntity = parent::createOrderItemEntity($transferItem, $transferOrder);
        $itemBundleEntity = $this->createItemBundle($transferItem);
        $itemEntity->setSalesOrderItemBundle($itemBundleEntity);
        $this->createItemBundleItems($itemBundleEntity);
        return $itemEntity;
    }

    /**
     * @param OrderItem $transferItem
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle
     */
    protected function createItemBundle(OrderItem $transferItem)
    {
        $itemBundleEntity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle();
        $itemBundleEntity->fromArray($transferItem->toArray());
        $itemBundleEntity->setBundleType($this->getBundleType());
        return $itemBundleEntity;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $itemEntity
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle $itemBundleEntity
     * @return void
     */
    protected function createItemBundleItems(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle $itemBundleEntity)
    {
        $sku = $itemBundleEntity->getSku();
        $bundleProducts = $this->facadeCatalog->getBundleProductsBySku($sku);
        /* @var $bundleProduct \SprykerFeature\Zed\Catalog\Persistence\Propel\PacCatalogProductBundleProduct */
        foreach($bundleProducts as $bundleProduct) {
            $productEntity = $bundleProduct->getBundleProductProduct();
            $productComposite = $this->facadeCatalog->getProductByEntity($productEntity);
            $itemBundleItemEntity = $this->createItemBundleItem($productComposite);
            $itemBundleItemEntity->setSalesOrderItemBundle($itemBundleEntity);
        }
    }

    /**
     * @param ReadOnlyProductComposite $productComposite
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundleItem
     */
    protected function createItemBundleItem(ReadOnlyProductComposite $productComposite)
    {
        $grossPrice = $this->facadeCatalog->getProductPriceBySku($productComposite->getSku());
        $itemBundleItemEntity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundleItem();
        $itemBundleItemEntity->setVariety($productComposite->getVariety());
        $itemBundleItemEntity->setGrossPrice($grossPrice);
        $itemBundleItemEntity->setTaxPercentage($productComposite[self::ATTRIBUTE_TAX_RATE]);
        $itemBundleItemEntity->setName($productComposite[self::ATTRIBUTE_NAME]);
        $itemBundleItemEntity->setSku($productComposite->getSku());
        return $itemBundleItemEntity;
    }

}
