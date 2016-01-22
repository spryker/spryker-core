<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;

class ProductOptionOrderSaver
{
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

                $salesOrderItemOptionEntity->fromArray($productOptionTransfer->toArray());
                $salesOrderItemOptionEntity->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());
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
}
