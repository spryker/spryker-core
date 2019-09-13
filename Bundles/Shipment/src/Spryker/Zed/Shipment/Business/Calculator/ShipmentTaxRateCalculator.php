<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Calculator;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentTaxRateCalculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    protected $taxFacade;

    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var string
     */
    protected $defaultTaxCountryIso2Code;

    /**
     * @var float
     */
    protected $defaultTaxRate;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentToTaxInterface $taxFacade,
        ShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentRepository = $shipmentRepository;
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
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            if ($shipmentGroupTransfer->getShipment() === null || $shipmentGroupTransfer->getShipment()->getMethod() === null) {
                continue;
            }

            $taxSetTransfer = $this->getTaxSetEffectiveRate($shipmentGroupTransfer->getShipment());

            $shipmentGroupTransfer = $this->setTaxRateForShipmentGroupItems($shipmentGroupTransfer, $taxSetTransfer);

            $expenseTransfer = $this->findQuoteExpenseByShipment($quoteTransfer, $shipmentGroupTransfer->getShipment());
            if ($expenseTransfer !== null) {
                $expenseTransfer->setTaxRate($taxSetTransfer->getEffectiveRate());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findQuoteExpenseByShipment(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ?ExpenseTransfer {
        $itemShipmentKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentKey = $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment());
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE
                && $expenseTransfer->getShipment() !== null
                && $expenseShipmentKey === $itemShipmentKey
            ) {
                return $expenseTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function setTaxRateForShipmentGroupItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        TaxSetTransfer $taxSetTransfer
    ): ShipmentGroupTransfer {
        $shipmentGroupTransfer
            ->getShipment()
            ->getMethod()
            ->setTaxRate($taxSetTransfer->getEffectiveRate());

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemTransfer
                ->getShipment()
                ->getMethod()
                ->setTaxRate($taxSetTransfer->getEffectiveRate());
        }

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    protected function getTaxSetEffectiveRate(ShipmentTransfer $shipmentTransfer): TaxSetTransfer
    {
        $taxSetTransfer = $this->findTaxSet($shipmentTransfer);

        if ($taxSetTransfer === null) {
            $taxSetTransfer = (new TaxSetTransfer())
                ->setEffectiveRate($this->getDefaultTaxRate());
        }

        return $taxSetTransfer;
    }

    /**
     * @return float
     */
    protected function getDefaultTaxRate(): float
    {
        if ($this->defaultTaxRate === null) {
            $this->defaultTaxRate = $this->taxFacade->getDefaultTaxRate();
        }

        return $this->defaultTaxRate;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    protected function findTaxSet(ShipmentTransfer $shipmentTransfer): ?TaxSetTransfer
    {
        $countryIso2Code = $this->getCountryIso2Code($shipmentTransfer->getShippingAddress());

        return $this->shipmentRepository
            ->findTaxSetByShipmentMethodAndCountryIso2Code(
                $shipmentTransfer->getMethod(),
                $countryIso2Code
            );
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return string
     */
    protected function getCountryIso2Code(?AddressTransfer $addressTransfer): string
    {
        if ($addressTransfer->getIso2Code() !== null) {
            return $addressTransfer->getIso2Code();
        }

        return $this->getDefaultTaxCountryIso2Code();
    }

    /**
     * @return string
     */
    protected function getDefaultTaxCountryIso2Code(): string
    {
        if ($this->defaultTaxCountryIso2Code === null) {
            $this->defaultTaxCountryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $this->defaultTaxCountryIso2Code;
    }
}
