<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class ItemProductOptionGrossPrice implements OrderAmountAggregatorInterface
{
    /**
     * @var SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * ItemProductOptionTotals constructor.
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $productOptions = $this->getHydratedSalesProductOptions($orderTransfer);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->setProductOptionAmountDefaults($itemTransfer);
            if (array_key_exists($itemTransfer->getIdSalesOrderItem(), $productOptions) === false) {
                continue;
            }

            $itemProductOptions = new \ArrayObject($productOptions[$itemTransfer->getIdSalesOrderItem()]);
            $this->setProductOptionTotals($itemProductOptions, $itemTransfer);

            $itemTransfer->setProductOptions($itemProductOptions);
        }
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return ProductOptionTransfer[]
     */
    protected function getHydratedSalesProductOptions(OrderTransfer $orderTransfer)
    {
        $salesOrderItems = $this->salesQueryContainer->querySalesOrderItem()
            ->findByFkSalesOrder($orderTransfer->getIdSalesOrder());

        $hydratedProductOptions = [];
        foreach ($salesOrderItems as $salesOrderItemEntity) {
            foreach ($salesOrderItemEntity->getOptions() as $productOptionEntity) {
                if (!isset($hydratedProductOptions[$productOptionEntity->getFkSalesOrderItem()])) {
                    $hydratedProductOptions[$productOptionEntity->getFkSalesOrderItem()] = [];
                }
                $productOptionTransfer = $this->hydrateProductOptionTransfer($productOptionEntity, $salesOrderItemEntity);
                $hydratedProductOptions[$productOptionEntity->getFkSalesOrderItem()][] = $productOptionTransfer;
            }
        }

        return $hydratedProductOptions;
    }

    /**
     * @param \ArrayObject|ProductOptionTransfer[] $itemProductOptions
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setProductOptionTotals(\ArrayObject $itemProductOptions, ItemTransfer $itemTransfer)
    {
        $totalProductOptionGrossSum = 0;
        $totalProductOptionGrossUnit = 0;
        $totalOptionsRefundableAmount = 0;
        foreach ($itemProductOptions as $productOptionTransfer) {
            $productOptionTransfer->requireUnitGrossPrice()->requireQuantity();
            $productOptionTransfer->setSumGrossPrice(
                $productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity()
            );

            $totalProductOptionGrossSum += $productOptionTransfer->getSumGrossPrice();
            $totalProductOptionGrossUnit += $productOptionTransfer->getUnitGrossPrice();
            $totalOptionsRefundableAmount += $productOptionTransfer->getRefundableAmount();
        }

        $itemTransfer->setUnitGrossPriceWithProductOptions($itemTransfer->getUnitGrossPrice() + $totalProductOptionGrossUnit);
        $itemTransfer->setSumGrossPriceWithProductOptions($itemTransfer->getSumGrossPrice() + $totalProductOptionGrossSum);
        $itemTransfer->setRefundableAmount($itemTransfer->getRefundableAmount() + $totalOptionsRefundableAmount);
    }

    /**
     * @param ProductOptionTransfer $productOptionTransfer
     * @param SpySalesOrderItemOption $productOptionEntity
     *
     * @return int
     */
    protected function getRefundableAmount(
        ProductOptionTransfer $productOptionTransfer,
        SpySalesOrderItemOption $productOptionEntity
    ) {
        return ($productOptionEntity->getGrossPrice() * $productOptionTransfer->getQuantity()) - $productOptionEntity->getCanceledAmount();
    }

    /**
     * @param SpySalesOrderItemOption $productOptionEntity
     * @param SpySalesOrderItem $salesOrderItemEntity
     *
     * @return ProductOptionTransfer
     */
    protected function hydrateProductOptionTransfer(
        SpySalesOrderItemOption $productOptionEntity,
        SpySalesOrderItem $salesOrderItemEntity
    ) {
        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->fromArray($productOptionEntity->toArray(), true);
        $productOptionTransfer->setUnitGrossPrice($productOptionEntity->getGrossPrice());
        $productOptionTransfer->setQuantity($salesOrderItemEntity->getQuantity());

        $refundableAmount = $this->getRefundableAmount($productOptionTransfer, $productOptionEntity);
        $productOptionTransfer->setRefundableAmount($refundableAmount);

        return $productOptionTransfer;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setProductOptionAmountDefaults(ItemTransfer $itemTransfer)
    {
        $itemTransfer->setUnitGrossPriceWithProductOptions($itemTransfer->getUnitGrossPrice());
        $itemTransfer->setSumGrossPriceWithProductOptions($itemTransfer->getSumGrossPrice());
    }
}
