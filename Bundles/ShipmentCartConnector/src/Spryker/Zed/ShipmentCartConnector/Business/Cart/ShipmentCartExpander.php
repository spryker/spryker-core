<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface;

class ShipmentCartExpander implements ShipmentCartExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface $priceFacade
     */
    public function __construct(
        ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCartConnectorToPriceFacadeInterface $priceFacade
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function updateShipmentPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();
        $availableShipmentMethods = $this->shipmentFacade->getAvailableMethods($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();
            $skipUpdate = (
                $shipmentTransfer === null
                || $this->isCurrencyChanged($shipmentTransfer, $quoteTransfer) === false
            );

            if ($skipUpdate) {
                continue;
            }

            $idShipmentMethod = $shipmentTransfer->getMethod()->getIdShipmentMethod();
            $shipmentMethodTransfer = $this->filterAvailableMethodById($idShipmentMethod, $availableShipmentMethods);

            if ($shipmentMethodTransfer === null) {
                return $cartChangeTransfer;
            }

            $this->updateShipmentExpenses($shipmentTransfer, $shipmentMethodTransfer, $quoteTransfer);

            $shipmentMethodTransfer->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());

            $quoteTransfer->getShipment()->setMethod($shipmentMethodTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $availableShipmentMethods
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function filterAvailableMethodById(
        int $idShipmentMethod,
        ShipmentMethodsTransfer $availableShipmentMethods
    ): ?ShipmentMethodTransfer {
        foreach ($availableShipmentMethods->getMethods() as $shipmentMethodTransfer) {
            if ($idShipmentMethod === $shipmentMethodTransfer->getIdShipmentMethod()) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCurrencyChanged(ShipmentTransfer $shipmentTransfer, QuoteTransfer $quoteTransfer): bool
    {
        if ($shipmentTransfer->getMethod() === null) {
            return false;
        }

        $shipmentCurrencyIsoCode = $shipmentTransfer->getMethod()->getCurrencyIsoCode();
        if ($shipmentCurrencyIsoCode !== $quoteTransfer->getCurrency()->getCode()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function setExpensePrice(
        ExpenseTransfer $shipmentExpenseTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        CurrencyTransfer $currencyTransfer,
        $priceMode
    ): void {
        $netModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            if ($moneyValueTransfer->getCurrency()->getCode() !== $currencyTransfer->getCode()) {
                continue;
            }

            if ($priceMode === $netModeIdentifier) {
                $shipmentExpenseTransfer->setUnitGrossPrice(0);
                $shipmentExpenseTransfer->setUnitNetPrice($moneyValueTransfer->getNetAmount());

                return;
            }

            $shipmentExpenseTransfer->setUnitNetPrice(0);
            $shipmentExpenseTransfer->setUnitGrossPrice($moneyValueTransfer->getGrossAmount());

            return;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function updateShipmentExpenses(
        ShipmentTransfer $shipmentTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $shipmentExpenseTypeIdentifier = $this->shipmentFacade->getShipmentExpenseTypeIdentifier();

        $shipmentExpense = $shipmentTransfer->getExpense();

        if ($shipmentExpense === null || $shipmentExpense->getType() !== $shipmentExpenseTypeIdentifier) {
            return;
        }

        $this->setExpensePrice(
            $shipmentExpense,
            $shipmentMethodTransfer,
            $quoteTransfer->getCurrency(),
            $quoteTransfer->getPriceMode()
        );
    }
}
