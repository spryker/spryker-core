<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Tax\Business\TaxFacade;

class OrderTotalsAggregator
{

    /**
     * @var SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var array|SpySalesOrderItem
     */
    protected $salesOrderItemEntityCache = [];

    /**
     * @var TaxFacade
     */
    protected $taxFacade;

    /**
     * @param SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer, TaxFacade $taxFacade)
    {
        $this->salesQueryContainer = $salesQueryContainer;
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function getGrandTotalByIdSalesOrder($idSalesOrder)
    {
        $salesOrderEntity = $this->getSalesOrderEntityByIdSalesOrder($idSalesOrder);

        $grandTotal = 0;
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $grandTotal += $this->getItemTotalAmountByIdSalesOrderItemAfterDiscounts(
                $salesOrderItemEntity->getIdSalesOrderItem()
            );
        }
        $grandTotal += $this->getExpensesTotalAmountByIdSalesOrder($idSalesOrder);

        return $grandTotal;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesOrder
     */
    protected function getSalesOrderEntityByIdSalesOrder($idSalesOrder)
    {
        return $this->salesQueryContainer->querySalesOrderById($idSalesOrder)->findOne();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getItemTotalAmountByIdSalesOrderItemAfterDiscounts($idSalesOrderItem)
    {
        $totalAmountBeforeDiscounts = $this->getItemTotalAmountByIdSalesOrderItem($idSalesOrderItem);
        $totalDiscountAmount = $this->getOrderItemTotalDiscountAmount($idSalesOrderItem);
        $totalAmount = $totalAmountBeforeDiscounts - $totalDiscountAmount;

        return $totalAmount;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getItemTotalAmountByIdSalesOrderItem($idSalesOrderItem)
    {
        $salesOrderItemEntity = $this->getSalesOrderItemEntityByIdSalesOrderItem($idSalesOrderItem);

        $totalAmount = $salesOrderItemEntity->getGrossPrice() * $salesOrderItemEntity->getQuantity();
        foreach ($salesOrderItemEntity->getOptions() as $salesOrderItemOptionEntity) {
            $totalAmount += $salesOrderItemOptionEntity->getGrossPrice() * $salesOrderItemEntity->getQuantity();
        }

        return $totalAmount;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return SpySalesOrderItem
     */
    protected function getSalesOrderItemEntityByIdSalesOrderItem($idSalesOrderItem)
    {
        if (!isset($this->salesOrderItemEntityCache[$idSalesOrderItem])) {
            $this->salesOrderItemEntityCache[$idSalesOrderItem] = $this->salesQueryContainer
                ->querySalesOrderItem()
                ->findOneByIdSalesOrderItem($idSalesOrderItem);
        }

        return $this->salesOrderItemEntityCache[$idSalesOrderItem];
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getOrderItemTotalDiscountAmount($idSalesOrderItem)
    {
        $calculatedDiscounts = $this->getDiscountsByIdSalesOrderItem($idSalesOrderItem);
        $totalDiscountAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalDiscountAmount += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        return $totalDiscountAmount;
    }

    /**
     *
     * @param int $idSalesOrderItem
     *
     * @return array|CalculatedDiscountTransfer[]
     */
    public function getDiscountsByIdSalesOrderItem($idSalesOrderItem)
    {
        $salesOrderItemEntity = $this->getSalesOrderItemEntityByIdSalesOrderItem($idSalesOrderItem);

        $calculatedDiscountsCollection = [];
        foreach ($salesOrderItemEntity->getDiscounts() as $salesDiscountEntity) {
            if (!isset($calculatedDiscountsCollection[$salesDiscountEntity->getName()])) {
                $calculatedDiscountsCollection[$salesDiscountEntity->getName()] = new CalculatedDiscountTransfer();
            }

            $calculatedDiscountTransfer = $this->getCalculatedDiscountTransfer(
                $calculatedDiscountsCollection,
                $salesDiscountEntity
            );

            $this->hydrateCalculatedDiscountTransfer(
                $calculatedDiscountTransfer,
                $salesOrderItemEntity,
                $salesDiscountEntity
            );
        }

        return $calculatedDiscountsCollection;
    }

    /**
     * @param CalculatedDiscountTransfer[] $calculatedDiscountsCollection
     * @param SpySalesDiscount $salesDiscountEntity
     *
     * @return CalculatedDiscountTransfer
     */
    protected function getCalculatedDiscountTransfer(
        $calculatedDiscountsCollection,
        SpySalesDiscount $salesDiscountEntity
    ) {
        return $calculatedDiscountsCollection[$salesDiscountEntity->getName()];
    }

    /**
     * @param CalculatedDiscountTransfer $calculatedDiscountTransfer
     * @param SpySalesOrderItem $salesOrderItemEntity
     * @param SpySalesDiscount $salesDiscountEntity
     *
     * @return void
     */
    protected function hydrateCalculatedDiscountTransfer(
        CalculatedDiscountTransfer $calculatedDiscountTransfer,
        SpySalesOrderItem $salesOrderItemEntity,
        SpySalesDiscount $salesDiscountEntity
    ) {
        $calculatedDiscountTransfer->fromArray($salesOrderItemEntity->toArray(), true);
        $calculatedDiscountTransfer->setDisplayName($salesDiscountEntity->getName());
        $calculatedDiscountTransfer->setUnitGrossAmount($salesDiscountEntity->getAmount());
        $calculatedDiscountTransfer->setSumGrossAmount(
            $salesDiscountEntity->getAmount() * $salesOrderItemEntity->getQuantity()
        );

        $this->setVoucherCodes($calculatedDiscountTransfer, $salesDiscountEntity);

    }

    /**
     * @param CalculatedDiscountTransfer $calculatedDiscountTransfer
     * @param SpySalesDiscount $salesDiscountEntity
     *
     * @return void
     */
    protected function setVoucherCodes(
        CalculatedDiscountTransfer $calculatedDiscountTransfer,
        SpySalesDiscount $salesDiscountEntity
    ) {
        foreach ($salesDiscountEntity->getDiscountCodes() as $discountCodeEntity) {
            $calculatedDiscountTransfer->setVoucherCode($discountCodeEntity->getCode());
        }
    }

    /**
     * @param int $idSalesOrderItem
     * @return int
     */
    public function getExpensesTotalAmountByIdSalesOrder($idSalesOrderItem)
    {
        $orderExpenses = $this->salesQueryContainer->querySalesExpensesByOrderId($idSalesOrderItem)->find();

        $totalExpenseAmount = 0;
        foreach ($orderExpenses as $salesExpenseEntity) {
            $totalExpenseAmount += $salesExpenseEntity->getGrossPrice();
        }

        return $totalExpenseAmount;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function getSubtotalByIdSalesOrderWithExpenses($idSalesOrder)
    {
        $subtotalBeforeExpenses = $this->getSubtotalByIdSalesOrder($idSalesOrder);
        $expensesTotalAmount = $this->getExpensesTotalAmountByIdSalesOrder($idSalesOrder);

        return $subtotalBeforeExpenses + $expensesTotalAmount;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function getSubtotalByIdSalesOrder($idSalesOrder)
    {
        $salesOrderEntity = $this->getSalesOrderEntityByIdSalesOrder($idSalesOrder);

        $subtotal = 0;
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $subtotal += $this->getItemTotalAmountByIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());
        }

        return $subtotal;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return CalculatedDiscountTransfer[]
     */
    public function getDiscountTotalsByIdSalesOrder($idSalesOrder)
    {
        $salesOrderEntity = $this->getSalesOrderEntityByIdSalesOrder($idSalesOrder);

        $discounts = [];
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $itemDiscounts = $this->getDiscountsByIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());
            foreach ($itemDiscounts as $name => $calculatedDiscountTransfer) {
                 if (array_key_exists($name, $discounts) === false) {
                     $discounts[$name] = $calculatedDiscountTransfer;
                     continue;
                 }
                 $discounts[$name]->setSumGrossAmount(
                     $discounts[$name]->getSumGrossAmount() + $calculatedDiscountTransfer->getSumGrossAmount()
                 );
            }
        }

        return $discounts;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return CalculatedDiscountTransfer[]
     */
    public function getDiscountTotalAmountByIdSalesOrder($idSalesOrder)
    {
        $salesOrderEntity = $this->getSalesOrderEntityByIdSalesOrder($idSalesOrder);

        $totalDiscountAmount = 0;
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $itemDiscounts = $this->getDiscountsByIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());
            foreach ($itemDiscounts as $name => $calculatedDiscountTransfer) {
                $totalDiscountAmount += $calculatedDiscountTransfer->getSumGrossAmount();
            }
        }

        return $totalDiscountAmount;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getTaxAmountByIdSalesOrderItem($idSalesOrderItem)
    {
        $salesOrderItemEntity = $this->getSalesOrderItemEntityByIdSalesOrderItem($idSalesOrderItem);

        if (empty($salesOrderItemEntity->getTaxRate())) {
            return 0;
        }

        $totalAmount = $this->getItemTotalAmountByIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());

        return $this->taxFacade->getTaxAmountFromGrossPrice($totalAmount, $salesOrderItemEntity->getTaxRate());
    }
}
