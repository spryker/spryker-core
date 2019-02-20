<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentTaxRateCalculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    protected $taxFacade;

    /**
     * @var array|\Generated\Shared\Transfer\ExpenseTransfer[]
     */
    protected $quoteShipmentExpenses;

    /**
     * @deprecated Will be removed in next major release.
     *
     * @var \Spryker\Zed\Shipment\Business\Calculator\QuoteDataBCForMultiShipmentAdapterInterface
     */
    protected $quoteDataBCForMultiShipmentAdapter;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     * @param \Spryker\Zed\Shipment\Business\Calculator\QuoteDataBCForMultiShipmentAdapterInterface $quoteDataBCForMultiShipmentAdapter
     */
    public function __construct(
        ShipmentQueryContainerInterface $shipmentQueryContainer,
        ShipmentToTaxInterface $taxFacade,
        QuoteDataBCForMultiShipmentAdapterInterface $quoteDataBCForMultiShipmentAdapter
    ) {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->taxFacade = $taxFacade;
        $this->quoteDataBCForMultiShipmentAdapter = $quoteDataBCForMultiShipmentAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        /**
         * @deprecated Will be removed in next major release.
         */
//        $quoteTransfer = $this->quoteDataBCForMultiShipmentAdapter->adapt($quoteTransfer);

        $this->quoteShipmentExpenses = $this->getQuoteExpenses($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getMethod() === null) {
                continue;
            }

            $taxRate = $this->getTaxRate($itemTransfer);
            $this->setItemShipmentTaxRate($itemTransfer, $taxRate);
            $this->setItemShipmentExpenseTaxRate($itemTransfer, $taxRate);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array|\Generated\Shared\Transfer\ExpenseTransfer[]
     */
    protected function getQuoteExpenses(QuoteTransfer $quoteTransfer): array
    {
        $quoteExpenses = [];

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE
                || $expenseTransfer->getShipment() === null
            ) {
                continue;
            }

            $quoteExpenses[$expenseTransfer->getShipment()->serialize()] = $expenseTransfer;
        }

        return $quoteExpenses;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param float $taxRate
     *
     * @return void
     */
    protected function setItemShipmentExpenseTaxRate(ItemTransfer $itemTransfer, float $taxRate): void
    {
        $expenseTransfer = $this->quoteShipmentExpenses[$itemTransfer->getShipment()->serialize()] ?? null;
        if ($expenseTransfer === null) {
            return;
        }

        $expenseTransfer->setTaxRate($taxRate);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param float $taxRate
     *
     * @return void
     */
    protected function setItemShipmentTaxRate(ItemTransfer $itemTransfer, float $taxRate): void
    {
        $itemTransfer
            ->getShipment()
            ->getMethod()
            ->setTaxRate($taxRate);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float
     */
    protected function getTaxRate(ItemTransfer $itemTransfer): float
    {
        $taxSetEntity = $this->findTaxSet($itemTransfer);
        if ($taxSetEntity !== null) {
            return (float)$taxSetEntity[ShipmentQueryContainer::COL_MAX_TAX_RATE];
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array|null
     */
    protected function findTaxSet(ItemTransfer $itemTransfer): ?array
    {
        $countryIso2Code = $this->getCountryIso2Code($itemTransfer);

        /** @var array|null $taxSet */
        $taxSet = $this->shipmentQueryContainer
            ->queryTaxSetByIdShipmentMethodAndCountryIso2Code(
                $itemTransfer->getShipment()->getMethod()->getIdShipmentMethod(),
                $countryIso2Code
            )
            ->findOne();

        return $taxSet;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getCountryIso2Code(ItemTransfer $itemTransfer): string
    {
        if ($itemTransfer->getShipment()->getShippingAddress()) {
            return $itemTransfer->getShipment()->getShippingAddress()->getIso2Code();
        }

        return $this->taxFacade->getDefaultTaxCountryIso2Code();
    }
}
