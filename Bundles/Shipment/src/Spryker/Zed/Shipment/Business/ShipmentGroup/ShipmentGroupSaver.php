<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentGroupSaver implements ShipmentGroupSaverInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface
     */
    protected $methodTransformer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     * @param \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface $methodTransformer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager,
        ShipmentToSalesFacadeInterface $salesFacade,
        ShipmentQueryContainerInterface $queryContainer,
        ShipmentToStoreInterface $storeFacade,
        ShipmentMethodTransformerInterface $methodTransformer,
        ShipmentToCurrencyInterface $currencyFacade
    ) {
        $this->entityManager = $entityManager;
        $this->salesFacade = $salesFacade;
        $this->queryContainer = $queryContainer;
        $this->storeFacade = $storeFacade;
        $this->methodTransformer = $methodTransformer;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $salesOrderTransfer
     *
     * @return void
     */
    public function saveShipmentGroup(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        OrderTransfer $salesOrderTransfer
    ): void {
        $shippingAddresTransfer = $shipmentGroupTransfer->getShipment()->getShippingAddress();
        $this->salesFacade->createOrderAddress($shippingAddresTransfer);

        $shipmentMethodTransfer = $shipmentGroupTransfer
            ->getShipment()
            ->getMethod();

        $shipmentGroupTransfer
            ->getShipment()
            ->setMethod($this->extendShipmentMethodTransfer($shipmentMethodTransfer, $salesOrderTransfer));

        $expenseTransfer = $this->createShippingExpenseTransfer(
            $shipmentGroupTransfer->getShipment()->getMethod(),
            $salesOrderTransfer
        );
        $expenseTransfer = $this->salesFacade->createSalesExpense($expenseTransfer);

        $shipmentGroupTransfer
            ->getShipment()
            ->setExpense($expenseTransfer);

        $salesOrderTransfer->addExpense($expenseTransfer);

        $idSalesShipment = $this->entityManager->createSalesShipment(
            $shipmentGroupTransfer->getShipment(),
            $salesOrderTransfer->getIdSalesOrder()
        );

        $this->updateSalesOrderItems($shipmentGroupTransfer, $idSalesShipment);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param int $idSalesShipment
     *
     * @return void
     */
    protected function updateSalesOrderItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        int $idSalesShipment
    ): void {
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $this->entityManager->updateSalesOrderItemFkShipment($itemTransfer, $idSalesShipment);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function extendShipmentMethodTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, OrderTransfer $orderTransfer): ShipmentMethodTransfer
    {
        $methodEntity = $this->queryContainer
            ->queryActiveMethodsWithMethodPricesAndCarrierById($shipmentMethodTransfer->getIdShipmentMethod())
            ->find()
            ->getFirst();

        if ($methodEntity === null) {
            return $shipmentMethodTransfer;
        }

        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();
        $methodPriceEntity = $this->queryContainer
            ->queryMethodPriceByShipmentMethodAndStoreCurrency(
                $methodEntity->getIdShipmentMethod(),
                $idStore,
                $this->getIdCurrencyByIsoCode($orderTransfer->getCurrencyIsoCode())
            )
            ->findOne();

        if ($methodPriceEntity === null) {
            return $shipmentMethodTransfer;
        }

        $price = $orderTransfer->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
            $methodPriceEntity->getDefaultGrossPrice() :
            $methodPriceEntity->getDefaultNetPrice();

        $shipmentMethodTransfer = $this->methodTransformer->transformEntityToTransfer($methodEntity);
        $shipmentMethodTransfer
            ->setCurrencyIsoCode($orderTransfer->getCurrencyIsoCode())
            ->setStoreCurrencyPrice($price);

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, OrderTransfer $orderTransfer)
    {
        $shipmentExpenseTransfer = (new ExpenseTransfer());

        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $this->setPrice(
            $shipmentExpenseTransfer,
            $shipmentMethodTransfer->getStoreCurrencyPrice(),
            $orderTransfer->getPriceMode()
        );
        $shipmentExpenseTransfer->setQuantity(1);

        $shipmentExpenseTransfer = $this->sanitizeExpenseSumPrices($shipmentExpenseTransfer);

        return $shipmentExpenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ExpenseTransfer $shipmentExpenseTransfer, $price, $priceMode)
    {
        if ($priceMode === ShipmentConstants::PRICE_MODE_NET) {
            $shipmentExpenseTransfer->setUnitGrossPrice(0);
            $shipmentExpenseTransfer->setUnitPriceToPayAggregation(0);
            $shipmentExpenseTransfer->setUnitPrice($price);
            $shipmentExpenseTransfer->setUnitNetPrice($price);
            return;
        }

        $shipmentExpenseTransfer->setUnitPriceToPayAggregation(0);
        $shipmentExpenseTransfer->setUnitNetPrice(0);
        $shipmentExpenseTransfer->setUnitPrice($price);
        $shipmentExpenseTransfer->setUnitGrossPrice($price);
    }

    /**
     * @deprecated For BC reasons the missing sum prices are mirrored from unit prices
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function sanitizeExpenseSumPrices(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $expenseTransfer->setSumGrossPrice($expenseTransfer->getSumGrossPrice() ?? $expenseTransfer->getUnitGrossPrice());
        $expenseTransfer->setSumNetPrice($expenseTransfer->getSumNetPrice() ?? $expenseTransfer->getUnitNetPrice());
        $expenseTransfer->setSumPrice($expenseTransfer->getSumPrice() ?? $expenseTransfer->getUnitPrice());
        $expenseTransfer->setSumTaxAmount($expenseTransfer->getSumTaxAmount() ?? $expenseTransfer->getUnitTaxAmount());
        $expenseTransfer->setSumDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation() ?? $expenseTransfer->getUnitDiscountAmountAggregation());
        $expenseTransfer->setSumPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation() ?? $expenseTransfer->getUnitPriceToPayAggregation());

        return $expenseTransfer;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return int
     */
    protected function getIdCurrencyByIsoCode($currencyIsoCode): int
    {
        return $this->currencyFacade
                ->fromIsoCode($currencyIsoCode)
                ->getIdCurrency();
    }
}
