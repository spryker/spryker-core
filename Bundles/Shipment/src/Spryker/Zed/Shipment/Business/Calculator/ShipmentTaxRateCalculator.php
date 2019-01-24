<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
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
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     */
    public function __construct(ShipmentQueryContainerInterface $shipmentQueryContainer, ShipmentToTaxInterface $taxFacade)
    {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        /**
         * @deprecated Will be removed in next major version after multiple shipment release.
         */
        $quoteTransfer = $this->adaptQuoteDataBCForMultiShipment($quoteTransfer);

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
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function adaptQuoteDataBCForMultiShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null) {
                return $quoteTransfer;
            }
            break;
        }

        $shippingAddress = $quoteTransfer->getShippingAddress();
        if ($shippingAddress === null) {
            return $quoteTransfer;
        }

        $shipmentExpenseTransfer = null;
        foreach ($quoteTransfer->getExpenses() as $key => $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentExpenseTransfer = $expenseTransfer;
            break;
        }

        $quoteShipment = $quoteTransfer->getShipment();
        if ($quoteShipment === null && $shipmentExpenseTransfer === null) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null
                && $itemTransfer->getShipment()->getExpense() !== null
                && $itemTransfer->getShipment()->getShippingAddress() !== null
            ) {
                continue;
            }

            $shipmentTransfer = $itemTransfer->getShipment() ?: $quoteShipment;
            if ($shipmentTransfer === null) {
                $shipmentTransfer = (new ShipmentTransfer())
                    ->setMethod(new ShipmentMethodTransfer());
            }

            if ($shipmentExpenseTransfer === null && $itemTransfer->getShipment() !== null) {
                $shipmentExpenseTransfer = $itemTransfer->getShipment()->getExpense();
            }

            $shipmentTransfer->setExpense($shipmentExpenseTransfer)
                ->setShippingAddress($shippingAddress);
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param float $taxRate
     *
     * @return void
     */
    protected function setItemShipmentExpenseTaxRate(ItemTransfer $itemTransfer, float $taxRate): void
    {
        if ($itemTransfer->getShipment()->getExpense() === null) {
            return;
        }

        $itemTransfer
            ->getShipment()
            ->getExpense()
            ->setTaxRate($taxRate);
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
