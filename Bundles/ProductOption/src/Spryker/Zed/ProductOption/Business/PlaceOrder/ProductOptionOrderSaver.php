<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\PlaceOrder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductOptionOrderSaver implements ProductOptionOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(ProductOptionToGlossaryFacadeInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderProductOptions(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($saveOrderTransfer) {
            $this->saveOrderProductOptionsTransaction($saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderProductOptionsTransaction(SaveOrderTransfer $saveOrderTransfer)
    {
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $this->saveOptions($itemTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption $salesOrderItemOptionEntity
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateSalesOrderItemOptionEntity(
        SpySalesOrderItemOption $salesOrderItemOptionEntity,
        ProductOptionTransfer $productOptionTransfer,
        ItemTransfer $itemTransfer
    ) {
        $salesOrderItemOptionEntity->fromArray($productOptionTransfer->toArray());
        $salesOrderItemOptionEntity->setGrossPrice($productOptionTransfer->getSumGrossPrice());
        $salesOrderItemOptionEntity->setNetPrice($productOptionTransfer->getSumNetPrice());
        $salesOrderItemOptionEntity->setTaxAmount($productOptionTransfer->getSumTaxAmount());
        $salesOrderItemOptionEntity->setDiscountAmountAggregation($productOptionTransfer->getSumDiscountAmountAggregation());
        $salesOrderItemOptionEntity->setPrice($productOptionTransfer->getSumPrice());

        $salesOrderItemOptionEntity->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function cloneOption(ProductOptionTransfer $productOptionTransfer)
    {
        $expandedProductOptionTransfer = clone $productOptionTransfer;
        $expandedProductOptionTransfer->setQuantity(1);
        $expandedProductOptionTransfer->setIdProductOptionValue(null);

        return $expandedProductOptionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function saveOptions(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->translateOption($productOptionTransfer);

            $salesOrderItemOptionEntity = $this->createSalesOrderItemOptionEntity();

            $this->hydrateSalesOrderItemOptionEntity(
                $salesOrderItemOptionEntity,
                $productOptionTransfer,
                $itemTransfer
            );

            $salesOrderItemOptionEntity->save();

            $productOptionTransfer->setIdSalesOrderItemOption(
                $salesOrderItemOptionEntity->getIdSalesOrderItemOption()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $expandedProductOptionTransfer
     *
     * @return void
     */
    protected function translateOption(ProductOptionTransfer $expandedProductOptionTransfer)
    {
        if ($this->glossaryFacade->hasTranslation($expandedProductOptionTransfer->getValue())) {
            $expandedProductOptionTransfer->setValue(
                $this->glossaryFacade->translate($expandedProductOptionTransfer->getValue())
            );
        }

        if ($this->glossaryFacade->hasTranslation($expandedProductOptionTransfer->getGroupName())) {
            $expandedProductOptionTransfer->setGroupName(
                $this->glossaryFacade->translate($expandedProductOptionTransfer->getGroupName())
            );
        }
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption
     */
    protected function createSalesOrderItemOptionEntity()
    {
        return new SpySalesOrderItemOption();
    }
}
