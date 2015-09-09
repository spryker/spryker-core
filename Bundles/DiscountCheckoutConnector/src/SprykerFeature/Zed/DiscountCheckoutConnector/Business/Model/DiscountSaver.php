<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\DiscountInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
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
     * @param CheckoutRequestInterface $request
     */
    public function saveDiscounts(OrderInterface $orderTransfer, CheckoutRequestInterface $request)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $discountCollection = $orderTransfer->getDiscounts();
        foreach ($discountCollection as $discountTransfer) {
            $this->saveDiscount($discountTransfer, $idSalesOrder);
        }
    }

    /**
     * @param DiscountInterface $discountTransfer
     * @param int $idSalesOrder
     */
    private function saveDiscount(DiscountInterface $discountTransfer, $idSalesOrder)
    {
        $discountEntity = $this->getDiscountVoucherEntityByCode($discountTransfer->getDisplayName());

        $salesDiscountEntity = $this->getSalesDiscountEntity();
        $salesDiscountEntity->setFkSalesOrder($idSalesOrder);
        $salesDiscountEntity->fromArray($discountTransfer->toArray());

        $salesDiscountCodeEntity = $this->getSalesDiscountCodeEntity();
        $salesDiscountCodeEntity->setDiscount($salesDiscountEntity);

        $salesDiscountCodeEntity->save();
    }

    /**
     * @param string $code
     *
     * @return SpyDiscountVoucher
     */
    private function getDiscountVoucherEntityByCode($code)
    {
        return $this->discountQueryContainer->queryVoucher($code)->findOne();
    }

    /**
     * @return SpySalesDiscount
     */
    private function getSalesDiscountEntity()
    {
        return new SpySalesDiscount();
    }

    /**
     * @return SpySalesDiscountCode
     */
    private function getSalesDiscountCodeEntity()
    {
        return new SpySalesDiscountCode();
    }

}
