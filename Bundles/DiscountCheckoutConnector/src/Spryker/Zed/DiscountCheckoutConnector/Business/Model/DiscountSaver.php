<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesDiscountCode;
use Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface;

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
     * @var DiscountCheckoutConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param DiscountQueryContainerInterface $discountQueryContainer
     * @param DiscountCheckoutConnectorToDiscountInterface $discountFacade
     */
    public function __construct(
        DiscountQueryContainerInterface $discountQueryContainer,
        DiscountCheckoutConnectorToDiscountInterface $discountFacade
    ) {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveDiscounts(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->saveOrderItemDiscounts($orderTransfer);
        $this->saveOrderExpenseDiscounts($orderTransfer);
        $this->discountFacade->useVoucherCodes($this->voucherCodesUsed);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveOrderItemDiscounts(OrderTransfer $orderTransfer)
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
     * @param ItemTransfer $orderItemTransfer
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @throws PropelException
     *
     * @return void
     */
    protected function saveOrderItemOptionDiscounts(ItemTransfer $orderItemTransfer, $idSalesOrder, $idSalesOrderItem)
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
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpySalesDiscount
     */
    protected function createSalesDiscountEntity(DiscountTransfer $discountTransfer)
    {
        $salesDiscountEntity = $this->getSalesDiscountEntity();
        $salesDiscountEntity->fromArray($discountTransfer->toArray());
        $salesDiscountEntity->setName('');

        return $salesDiscountEntity;
    }

    /**
     * @param SpySalesDiscount $salesDiscountEntity
     * @param DiscountTransfer $discountTransfer
     *
     * @return void
     */
    protected function saveDiscount(SpySalesDiscount $salesDiscountEntity, DiscountTransfer $discountTransfer)
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
     *
     * @return void
     */
    protected function persistSalesDiscount(SpySalesDiscount $salesDiscountEntity)
    {
        $salesDiscountEntity->save();
    }

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return bool
     */
    private function hasUsedCodes(DiscountTransfer $discountTransfer)
    {
        $usedCodes = $discountTransfer->getUsedCodes();

        return (count($usedCodes) > 0);
    }

    /**
     * @param DiscountTransfer $discountTransfer
     * @param SpySalesDiscount $salesDiscountEntity
     *
     * @return void
     */
    protected function saveUsedCodes(DiscountTransfer $discountTransfer, SpySalesDiscount $salesDiscountEntity)
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
     *
     * @return void
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
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveOrderExpenseDiscounts(OrderTransfer $orderTransfer)
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
