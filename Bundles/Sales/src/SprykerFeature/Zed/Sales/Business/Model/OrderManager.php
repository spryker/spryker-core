<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\SalesAddressTransfer;
use Propel\Runtime\Propel;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use SprykerFeature\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

class OrderManager
{

    /**
     * @var SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var OrderReferenceGeneratorInterface
     */
    protected $orderReferenceGenerator;

    public function __construct(
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator
    ) {
        $this->countryFacade = $countryFacade;
        $this->omsFacade = $omsFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function saveOrder(OrderTransfer $orderTransfer)
    {
        Propel::getConnection()->beginTransaction();

        $orderEntity = new SpySalesOrder();

        $orderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($orderTransfer));

        $orderEntity->setBillingAddress($this->saveAddressTransfer($orderTransfer->getBillingAddress()));
        $orderEntity->setShippingAddress($this->saveAddressTransfer($orderTransfer->getShippingAddress()));

        $orderEntity->setGrandTotal($orderTransfer->getTotals()->getGrandTotalWithDiscounts());
        $orderEntity->setSubtotal($orderTransfer->getTotals()->getSubtotal());

        $orderEntity->setEmail($orderTransfer->getEmail());
        $orderEntity->setFirstName($orderTransfer->getFirstName());
        $orderEntity->setLastName($orderTransfer->getLastName());

        $orderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($orderTransfer));

        $orderEntity->save();

        $fkOrderProcess = $this->omsFacade->getProcessEntity($orderTransfer->getProcess())->getIdOmsOrderProcess();

        foreach ($orderTransfer->getItems() as $item) {
            $itemEntity = new SpySalesOrderItem();
            $itemEntity->setName($item->getName());
            $itemEntity->setFkSalesOrder($orderEntity->getIdSalesOrder());
            $itemEntity->setFkOmsOrderItemState($this->omsFacade->getInitialStateEntity()->getIdOmsOrderItemState());
            $itemEntity->setSku($item->getSku());
            $itemEntity->setGrossPrice($item->getGrossPrice());
            $itemEntity->setPriceToPay($item->getPriceToPay());
            $itemEntity->setFkOmsOrderProcess($fkOrderProcess);
            $itemEntity->setQty(!is_null($item->getQuantity()) ? $item->getQuantity() : 1);

            $itemEntity->save();
            $item->setIdSalesOrderItem($itemEntity->getIdSalesOrderItem());
        }

        Propel::getConnection()->commit();

        $orderTransfer->setIdSalesOrder($orderEntity->getIdSalesOrder());
        $orderTransfer->setOrderReference($orderEntity->getOrderReference());
        
        return $orderTransfer;
    }

    /**
     * @param SalesAddressTransfer $address
     *
     * @return SpySalesOrderAddress
     */
    protected function saveAddressTransfer(SalesAddressTransfer $address = null)
    {
        if (is_null($address)) {
            return;
        }

        $addressEntity = new SpySalesOrderAddress();
        $addressEntity
            ->setFkCountry($this->countryFacade->getIdCountryByIso2Code($address->getIso2Code()))
            ->setFirstName($address->getFirstName())
            ->setLastName($address->getLastName())
            ->setAddress1($address->getAddress1())
            ->setAddress2($address->getAddress2())
            ->setAddress3($address->getAddress3())
            ->setCity($address->getCity())
            ->setZipCode($address->getZipCode())
        ;

        $addressEntity->save();
        $address->setIdSalesOrderAddress($addressEntity->getIdSalesOrderAddress());

        return $addressEntity;
    }

}
