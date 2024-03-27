<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
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
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected ShipmentToStoreInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentToTaxInterface $taxFacade,
        ShipmentServiceInterface $shipmentService,
        ShipmentToStoreInterface $storeFacade
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->taxFacade = $taxFacade;
        $this->shipmentService = $shipmentService;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $expenseTransfers = $this->recalculateByItemTransfersAndExpenseTransfers($quoteTransfer->getItems(), $quoteTransfer->getExpenses(), $quoteTransfer->getStore());
        $quoteTransfer->setExpenses($expenseTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateByCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $expenseTransfers = $this->recalculateByItemTransfersAndExpenseTransfers($calculableObjectTransfer->getItems(), $calculableObjectTransfer->getExpenses(), $calculableObjectTransfer->getStore());
        $calculableObjectTransfer->setExpenses($expenseTransfers);

        return $calculableObjectTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer>
     */
    protected function recalculateByItemTransfersAndExpenseTransfers(
        ArrayObject $itemTransfers,
        ArrayObject $expenseTransfers,
        ?StoreTransfer $storeTransfer = null
    ): ArrayObject {
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($itemTransfers);

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            if ($shipmentGroupTransfer->getShipment() === null || $shipmentGroupTransfer->getShipment()->getMethod() === null) {
                continue;
            }

            $taxSetTransfer = $this->getTaxSetEffectiveRate($shipmentGroupTransfer->getShipment(), $storeTransfer);

            $shipmentGroupTransfer = $this->setTaxRateForShipmentGroupItems($shipmentGroupTransfer, $taxSetTransfer);

            $expenseTransfer = $this->findQuoteExpenseByShipment($expenseTransfers, $shipmentGroupTransfer->getShipment());
            if ($expenseTransfer !== null) {
                $expenseTransfer->setTaxRate($taxSetTransfer->getEffectiveRate());
            }
        }

        return $expenseTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findQuoteExpenseByShipment(
        ArrayObject $expenseTransfers,
        ShipmentTransfer $shipmentTransfer
    ): ?ExpenseTransfer {
        $itemShipmentKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($expenseTransfers as $expenseTransfer) {
            if (!$expenseTransfer->getShipment()) {
                continue;
            }

            $expenseShipmentKey = $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment());

            if (
                $expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE
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
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    protected function getTaxSetEffectiveRate(ShipmentTransfer $shipmentTransfer, ?StoreTransfer $storeTransfer = null): TaxSetTransfer
    {
        $taxSetTransfer = $this->findTaxSet($shipmentTransfer, $storeTransfer);

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
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    protected function findTaxSet(ShipmentTransfer $shipmentTransfer, ?StoreTransfer $storeTransfer = null): ?TaxSetTransfer
    {
        $countryIso2Code = $this->getCountryIso2Code($shipmentTransfer->getShippingAddress(), $storeTransfer);

        return $this->shipmentRepository
            ->findTaxSetByShipmentMethodAndCountryIso2Code(
                $shipmentTransfer->getMethod(),
                $countryIso2Code,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    protected function getCountryIso2Code(?AddressTransfer $addressTransfer, ?StoreTransfer $storeTransfer = null): string
    {
        if ($addressTransfer && $addressTransfer->getIso2Code() !== null) {
            return $addressTransfer->getIso2Code();
        }

        return $this->getDefaultTaxCountryIso2Code($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    protected function getDefaultTaxCountryIso2Code(?StoreTransfer $storeTransfer = null): string
    {
        if ($this->defaultTaxCountryIso2Code === null) {
            if ($storeTransfer !== null) {
                $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
                $countries = $storeTransfer->getCountries();

                if ($countries) {
                    $this->defaultTaxCountryIso2Code = reset($countries);

                    return $this->defaultTaxCountryIso2Code;
                }
            }
            $this->defaultTaxCountryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $this->defaultTaxCountryIso2Code;
    }
}
