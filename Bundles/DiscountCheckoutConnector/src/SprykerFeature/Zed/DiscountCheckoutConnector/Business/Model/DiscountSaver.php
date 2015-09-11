<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\DiscountInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscountCode;

class DiscountSaver implements DiscountSaverInterface
{

    /**
     * @var DiscountQueryContainerInterface
     */
    private $discountQueryContainer;

    /**
     * @param DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     */
    public function saveDiscounts(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $discountCollection = $orderTransfer->getDiscounts();
        foreach ($discountCollection as $discountTransfer) {
            $this->saveDiscount($discountTransfer, $orderTransfer);
        }
    }

    /**
     * @param DiscountInterface $discountTransfer
     * @param OrderInterface $orderTransfer
     */
    protected function saveDiscount(DiscountInterface $discountTransfer, OrderInterface $orderTransfer)
    {
        $salesDiscountEntity = $this->getSalesDiscountEntity();
        $salesDiscountEntity->fromArray($discountTransfer->toArray());
        $salesDiscountEntity->setName('');
        $salesDiscountEntity->setAction('');
        $salesDiscountEntity->setFkSalesOrder($orderTransfer->getIdSalesOrder());

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
     * @throws \Propel\Runtime\Exception\PropelException
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
                $salesDiscountCodeEntity->setDiscount($salesDiscountEntity);

                $this->persistSalesDiscountCode($salesDiscountCodeEntity);
            }
        }
    }

    /**
     * @param SpySalesDiscountCode $salesDiscountCodeEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
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

}
