<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductBundleOrderSaver implements ProductBundleOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(ProductBundleToSalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderBundleItems(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $saveOrderTransfer) {
            $this->saveOrderBundleItemsTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function saveOrderBundleItemsTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $bundleItemsSaved = $this->saveSalesBundleProducts($quoteTransfer);
        $this->updateRelatedSalesOrderItems($saveOrderTransfer, $bundleItemsSaved);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function saveSalesBundleProducts(QuoteTransfer $quoteTransfer)
    {
        $bundleItemsSaved = [];
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $salesOrderItemBundleEntity = $this->mapSalesOrderItemBundleEntity($itemTransfer);
            $salesOrderItemBundleEntity->save();

            $bundleItemsSaved[$itemTransfer->getBundleItemIdentifier()] = $salesOrderItemBundleEntity->getIdSalesOrderItemBundle();
        }

        return $bundleItemsSaved;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle
     */
    protected function mapSalesOrderItemBundleEntity(ItemTransfer $itemTransfer)
    {
        $salesOrderItemBundleEntity = $this->createSalesOrderItemBundleEntity();
        $salesOrderItemBundleEntity->fromArray($itemTransfer->toArray());
        $salesOrderItemBundleEntity->setGrossPrice($itemTransfer->getUnitGrossPrice());
        $salesOrderItemBundleEntity->setNetPrice($itemTransfer->getUnitNetPrice());
        $salesOrderItemBundleEntity->setPrice($itemTransfer->getUnitPrice());
        $salesOrderItemBundleEntity->setImage($this->determineImage($itemTransfer));

        return $salesOrderItemBundleEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function determineImage(ItemTransfer $itemTransfer)
    {
        $images = $itemTransfer->getImages();

        if (count($images) === 0) {
            return null;
        }

        return $images[0]->getExternalUrlSmall();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param array $bundleItemsSaved
     *
     * @return void
     */
    protected function updateRelatedSalesOrderItems(SaveOrderTransfer $saveOrderTransfer, array $bundleItemsSaved)
    {
        $saveOrderTransfer->requireOrderItems();

        $orderItems = $saveOrderTransfer->getOrderItems();
        foreach ($orderItems as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier() || !isset($bundleItemsSaved[$itemTransfer->getRelatedBundleItemIdentifier()])) {
                continue;
            }
            $itemTransfer->requireIdSalesOrderItem();

            $salesOrderItemEntity = $this->findSalesOrderItem($itemTransfer->getIdSalesOrderItem());
            $salesOrderItemEntity->setFkSalesOrderItemBundle($bundleItemsSaved[$itemTransfer->getRelatedBundleItemIdentifier()]);
            $salesOrderItemEntity->save();
        }
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function findSalesOrderItem($idSalesOrderItem)
    {
        return $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @return \Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle
     */
    protected function createSalesOrderItemBundleEntity()
    {
        return new SpySalesOrderItemBundle();
    }
}
