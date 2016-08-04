<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;

class ProductOptionOrderSaver implements ProductOptionOrderSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface $glossaryFacade
     */
    public function __construct(ProductOptionToGlossaryInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function save(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $saveOrderTransfer = $checkoutResponse->getSaveOrder();

        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $salesOrderItemOptionEntity = $this->createSalesOrderItemOptionEntity();

                if ($this->glossaryFacade->hasTranslation($productOptionTransfer->getValue())) {
                    $productOptionTransfer->setValue(
                        $this->glossaryFacade->translate($productOptionTransfer->getValue())
                    );
                }

                $this->hydrateSalesOrderItemEntity($salesOrderItemOptionEntity, $productOptionTransfer, $itemTransfer);

                $salesOrderItemOptionEntity->save();

                $productOptionTransfer->setIdSalesOrderItemOption(
                    $salesOrderItemOptionEntity->getIdSalesOrderItemOption()
                );
            }
        }
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption
     */
    protected function createSalesOrderItemOptionEntity()
    {
        return new SpySalesOrderItemOption();
    }

    /**
     * @param SpySalesOrderItemOption $salesOrderItemOptionEntity
     * @param ProductOptionTransfer $productOptionTransfer
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateSalesOrderItemEntity(
        SpySalesOrderItemOption $salesOrderItemOptionEntity,
        ProductOptionTransfer $productOptionTransfer,
        ItemTransfer $itemTransfer
    ) {
        $salesOrderItemOptionEntity->fromArray($productOptionTransfer->toArray());
        $salesOrderItemOptionEntity->setGrossPrice($productOptionTransfer->getSumGrossPrice());
        $salesOrderItemOptionEntity->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());
    }

}
