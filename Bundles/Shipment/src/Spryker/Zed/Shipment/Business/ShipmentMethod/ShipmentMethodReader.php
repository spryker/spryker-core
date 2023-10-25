<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\Expander\ShipmentMethodExpanderInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentMethodReader implements ShipmentMethodReaderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\Shipment\Business\Expander\ShipmentMethodExpanderInterface
     */
    protected ShipmentMethodExpanderInterface $shipmentMethodExpander;

    /**
     * @var array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    protected static $currencyCache = [];

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Business\Expander\ShipmentMethodExpanderInterface $shipmentMethodExpander
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentToCurrencyInterface $currencyFacade,
        ShipmentMethodExpanderInterface $shipmentMethodExpander
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->currencyFacade = $currencyFacade;
        $this->shipmentMethodExpander = $shipmentMethodExpander;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodById(int $idShipmentMethod): ?ShipmentMethodTransfer
    {
        $shipmentMethodTransfer = $this->shipmentRepository
            ->findShipmentMethodByIdWithPricesAndCarrier($idShipmentMethod);

        if ($shipmentMethodTransfer === null) {
            return null;
        }

        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            $moneyValueTransfer->requireCurrency();
            $idCurrency = $moneyValueTransfer->getFkCurrency();

            $this->setCurrencyToMoneyValue($moneyValueTransfer, $idCurrency);
        }

        return $this->shipmentMethodExpander->expandShipmentMethodTransfer($shipmentMethodTransfer);
    }

    /**
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByName(string $shipmentMethodName): ?ShipmentMethodTransfer
    {
        $shipmentMethodTransfer = $this->shipmentRepository->findShipmentMethodByName($shipmentMethodName);
        if ($shipmentMethodTransfer === null) {
            return null;
        }

        return $this->shipmentMethodExpander->expandShipmentMethodTransfer($shipmentMethodTransfer);
    }

    /**
     * @param string $shipmentMethodKey
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByKey(string $shipmentMethodKey): ?ShipmentMethodTransfer
    {
        $shipmentMethodTransfer = $this->shipmentRepository->findShipmentMethodByKey($shipmentMethodKey);
        if ($shipmentMethodTransfer === null) {
            return null;
        }

        return $this->shipmentMethodExpander->expandShipmentMethodTransfer($shipmentMethodTransfer);
    }

    /**
     * @return list<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function getActiveShipmentMethods(): array
    {
        $shipmentMethodTransfers = $this->shipmentRepository->getActiveShipmentMethods();

        return $this->shipmentMethodExpander->expandShipmentMethodTransfers($shipmentMethodTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollection(ShipmentMethodCriteriaTransfer $shipmentMethodCriteriaTransfer): ShipmentMethodCollectionTransfer
    {
        $shipmentMethodsCollectionTransfer = $this->shipmentRepository->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);

        return $this->shipmentMethodExpander->expandShipmentMethodCollectionTransfer($shipmentMethodsCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param int $idCurrency
     *
     * @return void
     */
    protected function setCurrencyToMoneyValue(
        MoneyValueTransfer $moneyValueTransfer,
        int $idCurrency
    ): void {
        if (isset(static::$currencyCache[$idCurrency])) {
            $moneyValueTransfer->setCurrency(static::$currencyCache[$idCurrency]);

            return;
        }

        $currencyTransfer = $this->currencyFacade->getByIdCurrency($idCurrency);
        static::$currencyCache[$idCurrency] = $currencyTransfer;

        $moneyValueTransfer->setCurrency($currencyTransfer);
    }
}
