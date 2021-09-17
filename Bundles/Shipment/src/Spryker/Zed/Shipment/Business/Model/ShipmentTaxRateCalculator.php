<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\Shipment\Business\Calculator\ShipmentTaxRateCalculator} instead.
 */
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
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentQueryContainerInterface $shipmentQueryContainer,
        ShipmentToTaxInterface $taxFacade,
        ShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->taxFacade = $taxFacade;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShipment() === null || $quoteTransfer->getShipment()->getMethod() === null) {
            return;
        }

        $shipmentTransfer = $quoteTransfer->getShipment();
        $taxRate = $this->getTaxRate($shipmentTransfer, $quoteTransfer->getShippingAddress());

        $shipmentMethodTransfer = $shipmentTransfer
            ->getMethod()
            ->setTaxRate($taxRate);
        $quoteTransfer->setShipment($shipmentTransfer->setMethod($shipmentMethodTransfer));

        $expenseTransfers = $this->setQuoteExpenseTaxRate($shipmentTransfer, $quoteTransfer->getExpenses(), $taxRate);
        $quoteTransfer->setExpenses($expenseTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateByCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        if ($calculableObjectTransfer->getShipment() === null || $calculableObjectTransfer->getShipment()->getMethod() === null) {
            return $calculableObjectTransfer;
        }

        $shipmentTransfer = $calculableObjectTransfer->getShipment();
        $taxRate = $this->getTaxRate($shipmentTransfer, $calculableObjectTransfer->getShippingAddress());

        $shipmentMethodTransfer = $shipmentTransfer
            ->getMethod()
            ->setTaxRate($taxRate);
        $calculableObjectTransfer->setShipment($shipmentTransfer->setMethod($shipmentMethodTransfer));

        $expenseTransfers = $this->setQuoteExpenseTaxRate($shipmentTransfer, $calculableObjectTransfer->getExpenses(), $taxRate);
        $calculableObjectTransfer->setExpenses($expenseTransfers);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     * @param float $taxRate
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer>
     */
    protected function setQuoteExpenseTaxRate(ShipmentTransfer $shipmentTransfer, ArrayObject $expenseTransfers, float $taxRate): ArrayObject
    {
        $shipmentMethodName = $shipmentTransfer
            ->requireMethod()
            ->getMethod()
            ->getName();

        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE && $expenseTransfer->getName() === $shipmentMethodName) {
                $expenseTransfer->setTaxRate($taxRate);
            }
        }

        return $expenseTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     *
     * @return float
     */
    protected function getTaxRate(ShipmentTransfer $shipmentTransfer, ?AddressTransfer $shippingAddressTransfer): float
    {
        $countryIsoCode = $this->getCountryIso2Code($shippingAddressTransfer);
        $taxSetEntity = $this->findTaxSet($shipmentTransfer, $countryIsoCode);
        if ($taxSetEntity !== null && isset($taxSetEntity[ShipmentQueryContainer::COL_MAX_TAX_RATE])) {
            return (float)$taxSetEntity[ShipmentQueryContainer::COL_MAX_TAX_RATE];
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $countryIso2Code
     *
     * @return array<string>|null
     */
    protected function findTaxSet(ShipmentTransfer $shipmentTransfer, string $countryIso2Code)
    {
        /**
         * @var array<string>|null $taxSet
         */
        $taxSet = $this->shipmentQueryContainer
            ->queryTaxSetByIdShipmentMethodAndCountryIso2Code(
                $shipmentTransfer->requireMethod()->getMethod()->getIdShipmentMethod(),
                $countryIso2Code
            )
            ->findOne();

        return $taxSet;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     *
     * @return string
     */
    protected function getCountryIso2Code(?AddressTransfer $shippingAddressTransfer): string
    {
        if ($shippingAddressTransfer) {
            return $shippingAddressTransfer->getIso2Code();
        }

        return $this->taxFacade->getDefaultTaxCountryIso2Code();
    }
}
