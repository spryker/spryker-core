<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
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
     * @var \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected static $currencyCache = [];

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentToCurrencyInterface $currencyFacade
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->currencyFacade = $currencyFacade;
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

        return $shipmentMethodTransfer;
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
