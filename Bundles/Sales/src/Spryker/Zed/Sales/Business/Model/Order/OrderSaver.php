<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Propel;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\SalesConfig;

class OrderSaver implements OrderSaverInterface
{

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface
     */
    protected $orderReferenceGenerator;

    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected $salesConfiguration;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface $orderReferenceGenerator
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfiguration
     */
    public function __construct(
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator,
        SalesConfig $salesConfiguration
    ) {
        $this->countryFacade = $countryFacade;
        $this->omsFacade = $omsFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
        $this->salesConfiguration = $salesConfiguration;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @throws \Exception
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->assertOrderRequirements($quoteTransfer);

        Propel::getConnection()->beginTransaction();

        $salesOrderEntity = $this->saveOrderEntity($quoteTransfer);
        $this->saveOrderItems($quoteTransfer, $salesOrderEntity);

        Propel::getConnection()->commit();

        $this->hydrateCheckoutResponseTransfer($checkoutResponseTransfer, $quoteTransfer, $salesOrderEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function saveOrderEntity(QuoteTransfer $quoteTransfer)
    {
        $salesOrderEntity = $this->createSalesOrderEntity();
        $this->hydrateSalesOrderEntity($quoteTransfer, $salesOrderEntity);
        $this->hydrateAddresses($quoteTransfer, $salesOrderEntity);
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateAddresses(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $billingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getBillingAddress());
        $shippingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getShippingAddress());

        $salesOrderEntity->setBillingAddress($billingAddressEntity);
        $salesOrderEntity->setShippingAddress($shippingAddressEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function saveSalesOrderAddress(AddressTransfer $addressTransfer)
    {
        $salesOrderAddressEntity = $this->createSalesOrderAddressEntity();
        $this->hydrateSalesOrderAddress($addressTransfer, $salesOrderAddressEntity);
        $salesOrderAddressEntity->save();

        $addressTransfer->setIdSalesOrderAddress($salesOrderAddressEntity->getIdSalesOrderAddress());

        return $salesOrderAddressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderAddress(AddressTransfer $addressTransfer, SpySalesOrderAddress $salesOrderAddressEntity)
    {
        $salesOrderAddressEntity->fromArray($addressTransfer->toArray());
        $salesOrderAddressEntity->setFkCountry(
            $this->countryFacade->getIdCountryByIso2Code($addressTransfer->getIso2Code())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderEntity(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $salesOrderEntity->setFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());
        $this->hydrateSalesOrderCustomer($quoteTransfer, $salesOrderEntity);
        $salesOrderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($quoteTransfer));
        $salesOrderEntity->setIsTest($this->salesConfiguration->isTestOrder($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderCustomer(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $customerTransfer = $quoteTransfer->getCustomer();

        $salesOrderEntity->setFkCustomer($customerTransfer->getIdCustomer());
        $salesOrderEntity->setEmail($customerTransfer->getEmail());
        $salesOrderEntity->setFirstName($customerTransfer->getFirstName());
        $salesOrderEntity->setLastName($customerTransfer->getLastName());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function saveOrderItems(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $items = $this->expandItems($quoteTransfer->getItems());
        $quoteTransfer->setItems($items);

        foreach ($items as $itemTransfer) {
            $this->assertItemRequirements($itemTransfer);

            $salesOrderItemEntity = $this->createSalesOrderItemEntity();
            $this->hydrateSalesOrderItemEntity($salesOrderEntity, $quoteTransfer, $salesOrderItemEntity, $itemTransfer);
            $salesOrderItemEntity->save();
            $itemTransfer->setIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function expandItems(\ArrayObject $items)
    {
        $expandedItems = new \ArrayObject();
        foreach ($items as $itemTransfer) {
            $quantity = $itemTransfer->getQuantity();
            for ($i = 1; $quantity >= $i; $i++) {
                $expandedItemTransfer = clone $itemTransfer;
                $expandedItemTransfer->setQuantity(1);
                $expandedItems->append($expandedItemTransfer);
            }
        }

        return $expandedItems;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateSalesOrderItemEntity(
        SpySalesOrder $salesOrderEntity,
        QuoteTransfer $quoteTransfer,
        SpySalesOrderItem $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ) {
        $processEntity = $this->getProcessEntity($quoteTransfer, $itemTransfer);
        $initialStateEntity = $this->omsFacade->getInitialStateEntity();

        $salesOrderItemEntity->fromArray($itemTransfer->toArray());
        $salesOrderItemEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItemEntity->setFkOmsOrderItemState($initialStateEntity->getIdOmsOrderItemState());
        $salesOrderItemEntity->setGrossPrice($itemTransfer->getUnitGrossPrice());
        $salesOrderItemEntity->setProcess($processEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function getProcessEntity(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        $processName = $this->salesConfiguration->determineProcessForOrderItem($quoteTransfer, $itemTransfer);
        $processEntity = $this->omsFacade->getProcessEntity($processName);

        return $processEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateCheckoutResponseTransfer(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity
    ) {
        $saveOrderTransfer = $this->getSaveOrderTransfer($checkoutResponseTransfer);
        $this->hydrateSaveOrderTransfer($saveOrderTransfer, $quoteTransfer);
        $saveOrderTransfer->fromArray($salesOrderEntity->toArray(), true);

        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function hydrateSaveOrderTransfer(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $saveOrderTransfer->addOrderItem(clone $itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function getSaveOrderTransfer(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $saveOrderTransfer = $checkoutResponseTransfer->getSaveOrder();
        if ($saveOrderTransfer === null) {
            $saveOrderTransfer = $this->createSaveOrderTransfer();
        }

        return $saveOrderTransfer;
    }


    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSalesOrderEntity()
    {
        return new SpySalesOrder();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemEntity()
    {
        return new SpySalesOrderItem();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        return new OrderTransfer();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function createSalesOrderAddressEntity()
    {
        return new SpySalesOrderAddress();
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createSaveOrderTransfer()
    {
        return new SaveOrderTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireUnitGrossPrice()
            ->requireQuantity()
            ->requireName()
            ->requireSku();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertOrderRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireItems()
            ->requireTotals();
    }



}
