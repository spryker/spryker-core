<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesDiscountCode;
use Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountSaver implements DiscountSaverInterface
{

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var array|string[]
     */
    protected $voucherCodesUsed = [];

    /**
     * @var \Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface $discountFacade
     */
    public function __construct(
        DiscountQueryContainerInterface $discountQueryContainer,
        DiscountCheckoutConnectorToDiscountInterface $discountFacade
    ) {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
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
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscount
     */
    protected function createSalesDiscountEntity(DiscountTransfer $discountTransfer)
    {
        $salesDiscountEntity = $this->getSalesDiscountEntity();
        $salesDiscountEntity->fromArray($discountTransfer->toArray());
        $salesDiscountEntity->setName('');

        return $salesDiscountEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesDiscountEntity
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscount
     */
    protected function getSalesDiscountEntity()
    {
        return new SpySalesDiscount();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesDiscountEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function persistSalesDiscount(SpySalesDiscount $salesDiscountEntity)
    {
        $salesDiscountEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return bool
     */
    private function hasUsedCodes(DiscountTransfer $discountTransfer)
    {
        $usedCodes = $discountTransfer->getUsedCodes();

        return (count($usedCodes) > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesDiscountEntity
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscountCode $salesDiscountCodeEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    protected function getDiscountVoucherEntityByCode($code)
    {
        return $this->discountQueryContainer->queryVoucher($code)->findOne();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountCode
     */
    protected function getSalesDiscountCodeEntity()
    {
        return new SpySalesDiscountCode();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
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
