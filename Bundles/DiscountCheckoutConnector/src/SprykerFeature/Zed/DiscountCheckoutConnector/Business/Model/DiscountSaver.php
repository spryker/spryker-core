<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\DiscountInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\DiscountCheckoutConnector\Dependency\Facade\DiscountCheckoutConnectorToDiscountInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscountCode;

class DiscountSaver implements DiscountSaverInterface
{

    /**
     * @var DiscountCheckoutConnectorToDiscountInterface
     */
    private $discountFacade;

    /**
     * @param DiscountCheckoutConnectorToDiscountInterface $discountFacade
     */
    public function __construct(DiscountCheckoutConnectorToDiscountInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
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
        $discountEntity = $this->getDiscountEntityByDisplayName($discountTransfer->getDisplayName());

        $salesDiscountEntity = $this->getSalesDiscountEntity();
        $salesDiscountEntity->setFkSalesOrder($idSalesOrder);
        $salesDiscountEntity->fromArray($discountTransfer->toArray());

        $salesDiscountCodeEntity = $this->getSalesDiscountCodeEntity();
        $salesDiscountCodeEntity->setDiscount($salesDiscountEntity);

        $salesDiscountCodeEntity->save();
    }

    /**
     * @param string $displayName
     *
     * @return SpyDiscount
     */
    private function getDiscountEntityByDisplayName($displayName)
    {
        return $this->discountFacade->getDiscountByDisplayName($displayName);
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
