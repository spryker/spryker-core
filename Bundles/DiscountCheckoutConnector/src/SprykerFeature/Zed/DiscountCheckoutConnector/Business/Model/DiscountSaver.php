<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\DiscountInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;
use Generated\Shared\Sales\ItemInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscountCode;
use SprykerFeature\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;

class DiscountSaver implements DiscountSaverInterface
{

    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var array|string[]
     */
    protected $voucherCodesUsed = [];

    /**
     * @var DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @param DiscountQueryContainerInterface $discountQueryContainer
     * @param DiscountFacadeInterface $discountFacade
     */
    public function __construct(
        DiscountQueryContainerInterface $discountQueryContainer,
        DiscountFacadeInterface $discountFacade
    ) {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     */
    public function saveDiscounts(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->saveOrderDiscounts($orderTransfer);
        $this->saveOrderItemDiscounts($orderTransfer);
        $this->saveOrderExpenseDiscounts($orderTransfer);
        $this->discountFacade->useVoucherCodes($this->voucherCodesUsed);
    }

    /**
     * @param OrderInterface $orderTransfer
     */
    protected function saveOrderDiscounts(OrderInterface $orderTransfer)
    {
        $discountCollection = $orderTransfer->getDiscounts();
        foreach ($discountCollection as $discountTransfer) {
            $salesDiscountEntity = $this->createSalesDiscountEntity($discountTransfer);
            $salesDiscountEntity->setFkSalesOrder($orderTransfer->getIdSalesOrder());
            $this->saveDiscount($salesDiscountEntity, $discountTransfer);
        }
    }

    /**
     * @param OrderInterface $orderTransfer
     */
    protected function saveOrderItemDiscounts(OrderInterface $orderTransfer)
    {
        $orderItemCollection = $orderTransfer->getItems();
        foreach ($orderItemCollection as $orderItemTransfer) {
            $discountCollection = $orderItemTransfer->getDiscounts();
            foreach ($discountCollection as $discountTransfer) {
                $salesDiscountEntity = $this->createSalesDiscountEntity($discountTransfer);
                $salesDiscountEntity->setFkSalesOrder($orderTransfer->getIdSalesOrder());
                $salesDiscountEntity->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
                $this->saveDiscount($salesDiscountEntity, $discountTransfer);
            }
            $this->saveOrderItemOptionDiscounts(
                $orderItemTransfer,
                $orderTransfer->getIdSalesOrder(),
                $orderItemTransfer->getIdSalesOrderItem()
            );
        }
    }

    /**
     * @param ItemInterface $orderItemTransfer
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @throws PropelException
     */
    protected function saveOrderItemOptionDiscounts(ItemInterface $orderItemTransfer, $idSalesOrder, $idSalesOrderItem)
    {
        foreach ($orderItemTransfer->getProductOptions() as $productOptionTransfer) {
            foreach ($productOptionTransfer->getDiscounts() as $productOptionDiscountTransfer) {
                $salesDiscountEntity = $this->createSalesDiscountEntity($productOptionDiscountTransfer);
                $salesDiscountEntity->setFkSalesOrder($idSalesOrder);
                $salesDiscountEntity->setFkSalesOrderItem($idSalesOrderItem);
                $salesDiscountEntity->setFkSalesOrderItemOption($productOptionTransfer->getIdSalesOrderItemOption());
                $this->saveDiscount($salesDiscountEntity, $productOptionDiscountTransfer);
            }
        }
    }

    /**
     * @param DiscountInterface $discountTransfer
     *
     * @return SpySalesDiscount
     */
    protected function createSalesDiscountEntity(DiscountInterface $discountTransfer)
    {
        $salesDiscountEntity = $this->getSalesDiscountEntity();
        $salesDiscountEntity->fromArray($discountTransfer->toArray());
        $salesDiscountEntity->setName('');

        return $salesDiscountEntity;
    }

    /**
     * @param SpySalesDiscount $salesDiscountEntity
     * @param DiscountInterface $discountTransfer
     */
    protected function saveDiscount(SpySalesDiscount $salesDiscountEntity, DiscountInterface $discountTransfer)
    {
        $this->persistSalesDiscount($salesDiscountEntity);

        if ($this->hasUsedCodes($discountTransfer)) {
            $this->saveUsedCodes($discountTransfer, $salesDiscountEntity);
        }
    }

    /**
     * @return SpySalesDiscount
     */
    protected function getSalesDiscountEntity()
    {
        return new SpySalesDiscount();
    }

    /**
     * @param SpySalesDiscount $salesDiscountEntity
     *
     * @throws PropelException
     */
    protected function persistSalesDiscount(SpySalesDiscount $salesDiscountEntity)
    {
        $salesDiscountEntity->save();
    }

    /**
     * @param DiscountInterface $discountTransfer
     *
     * @return bool
     */
    private function hasUsedCodes(DiscountInterface $discountTransfer)
    {
        $usedCodes = $discountTransfer->getUsedCodes();

        return (count($usedCodes) > 0);
    }

    /**
     * @param DiscountInterface $discountTransfer
     * @param SpySalesDiscount $salesDiscountEntity
     */
    protected function saveUsedCodes(DiscountInterface $discountTransfer, SpySalesDiscount $salesDiscountEntity)
    {
        foreach ($discountTransfer->getUsedCodes() as $code) {
            $discountVoucherEntity = $this->getDiscountVoucherEntityByCode($code);
            if ($discountVoucherEntity) {
                $salesDiscountCodeEntity = $this->getSalesDiscountCodeEntity();
                $salesDiscountCodeEntity->fromArray($discountVoucherEntity->toArray());
                $salesDiscountCodeEntity->setCodepoolName(
                    $discountVoucherEntity->getVoucherPool()->getName()
                );
                $salesDiscountCodeEntity->setDiscount($salesDiscountEntity);

                if (!isset($this->voucherCodesUsed[$code])) {
                    $this->voucherCodesUsed[$code] = $code;
                }

                $this->persistSalesDiscountCode($salesDiscountCodeEntity);
            }
        }
    }

    /**
     * @param SpySalesDiscountCode $salesDiscountCodeEntity
     *
     * @throws PropelException
     */
    protected function persistSalesDiscountCode(SpySalesDiscountCode $salesDiscountCodeEntity)
    {
        $salesDiscountCodeEntity->save();
    }

    /**
     * @param string $code
     *
     * @return SpyDiscountVoucher
     */
    protected function getDiscountVoucherEntityByCode($code)
    {
        return $this->discountQueryContainer->queryVoucher($code)->findOne();
    }

    /**
     * @return SpySalesDiscountCode
     */
    protected function getSalesDiscountCodeEntity()
    {
        return new SpySalesDiscountCode();
    }

    /**
     * @param OrderInterface $orderTransfer
     */
    protected function saveOrderExpenseDiscounts(OrderInterface $orderTransfer)
    {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            foreach ($expenseTransfer->getDiscounts() as $discountTransfer) {
                $salesDiscountEntity = $this->createSalesDiscountEntity($discountTransfer);
                $salesDiscountEntity->setFkSalesOrder($orderTransfer->getIdSalesOrder());
                $salesDiscountEntity->setFkSalesExpense($expenseTransfer->getIdSalesExpense());
                $this->saveDiscount($salesDiscountEntity, $discountTransfer);
            }
        }
    }
}
