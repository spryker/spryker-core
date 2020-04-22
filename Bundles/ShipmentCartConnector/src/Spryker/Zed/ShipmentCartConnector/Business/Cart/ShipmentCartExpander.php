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
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentCartConnector\Business\Calculator\ShipmentMethodPriceCalculatorInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface;

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
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Business\Calculator\ShipmentMethodPriceCalculatorInterface
     */
    protected $shipmentMethodPriceCalculator;

    /**
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\ShipmentCartConnector\Business\Calculator\ShipmentMethodPriceCalculatorInterface $shipmentMethodPriceCalculator
     */
    public function __construct(
        ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCartConnectorToPriceFacadeInterface $priceFacade,
        ShipmentCartConnectorToShipmentServiceInterface $shipmentService,
        ShipmentMethodPriceCalculatorInterface $shipmentMethodPriceCalculator
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->priceFacade = $priceFacade;
        $this->shipmentService = $shipmentService;
        $this->shipmentMethodPriceCalculator = $shipmentMethodPriceCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function updateShipmentPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();

        $availableShipmentMethods = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);
        $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $shipmentGroupShipmentTransfer = $shipmentGroupTransfer->getShipment();
            if ($shipmentGroupShipmentTransfer === null) {
                continue;
            }

            if (!$this->isShipmentExpenseUpdateNeeded($quoteTransfer, $shipmentGroupTransfer)) {
                continue;
            }

            $availableShipmentMethodsByShipmentGroup = $this->findAvailableShipmentMethodsByShipment(
                $availableShipmentMethods,
                $shipmentGroupShipmentTransfer
            );

            if ($availableShipmentMethodsByShipmentGroup === null) {
                continue;
            }

            $shipmentGroupShipmentMethodTransfer = $shipmentGroupShipmentTransfer->getMethod();
            $availableShipmentMethodByShipmentGroup = $this->findAvailableShipmentMethodByIdShipmentMethod(
                $availableShipmentMethodsByShipmentGroup,
                $shipmentGroupShipmentMethodTransfer->getIdShipmentMethod()
            );

            if ($availableShipmentMethodByShipmentGroup === null) {
                continue;
            }

            $availableShipmentMethodByShipmentGroup->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());
            $availableShipmentMethodByShipmentGroup->setSourcePrice($shipmentGroupShipmentMethodTransfer->getSourcePrice());

            $shipmentGroupTransfer->getShipment()->setMethod(
                (new ShipmentMethodTransfer())->fromArray($availableShipmentMethodByShipmentGroup->toArray())
            );

            $quoteTransfer = $this->updateShipmentExpense($quoteTransfer, $shipmentGroupTransfer);
        }

        return $cartChangeTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    protected function isShipmentExpenseUpdateNeeded(QuoteTransfer $quoteTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        $shipmentTransfer = $shipmentGroupTransfer->getShipment();

        if (!$shipmentTransfer || !$shipmentTransfer->getMethod() || !$shipmentTransfer->getMethod()->getIdShipmentMethod()) {
            return false;
        }

        $shipmentMethodTransfer = $shipmentTransfer->getMethod();

        if ($this->isCurrencyChanged($shipmentTransfer, $quoteTransfer) || $shipmentMethodTransfer->getSourcePrice()) {
            return true;
        }

        if ($this->isDifferentQuoteShipmentAndExpenseShipmentPrices($quoteTransfer, $shipmentGroupTransfer)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    protected function isDifferentQuoteShipmentAndExpenseShipmentPrices(QuoteTransfer $quoteTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        $expenseTransfer = $this->findExpenseByShipmentGroup($quoteTransfer, $shipmentGroupTransfer);

        if (!$expenseTransfer || !$expenseTransfer->getShipment()) {
            return false;
        }

        $shipmentStoreCurrencyPrice = $shipmentGroupTransfer
            ->getShipment()
            ->getMethod()
            ->getStoreCurrencyPrice();

        return $shipmentStoreCurrencyPrice !== $expenseTransfer->getSumPrice();
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
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollection
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|null
     */
    protected function findAvailableShipmentMethodsByShipment(
        ShipmentMethodsCollectionTransfer $shipmentMethodsCollection,
        ShipmentTransfer $shipmentTransfer
    ): ?ShipmentMethodsTransfer {
        $shipmentHash = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($shipmentMethodsCollection->getShipmentMethods() as $shipmentMethodsTransfer) {
            if ($shipmentMethodsTransfer->getShipmentHash() === $shipmentHash) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $availableShipmentMethods
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findAvailableShipmentMethodByIdShipmentMethod(
        ShipmentMethodsTransfer $availableShipmentMethods,
        int $idShipmentMethod
    ): ?ShipmentMethodTransfer {
        foreach ($availableShipmentMethods->getMethods() as $shipmentMethodTransfer) {
            if ($idShipmentMethod === $shipmentMethodTransfer->getIdShipmentMethod()) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateShipmentExpense(QuoteTransfer $quoteTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): QuoteTransfer
    {
        $expenseTransfer = $this->findExpenseByShipmentGroup($quoteTransfer, $shipmentGroupTransfer);
        if ($expenseTransfer === null) {
            return $quoteTransfer;
        }

        $expenseTransfer->setShipment($shipmentGroupTransfer->getShipment());

        $this->setExpensePrice(
            $expenseTransfer,
            $quoteTransfer->getCurrency(),
            $quoteTransfer->getPriceMode()
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findExpenseByShipmentGroup(QuoteTransfer $quoteTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): ?ExpenseTransfer
    {
        $shipmentHashKey = $shipmentGroupTransfer->getHash();
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentHashKey = $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment());
            if ($shipmentHashKey === $expenseShipmentHashKey) {
                return $expenseTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function setExpensePrice(
        ExpenseTransfer $shipmentExpenseTransfer,
        CurrencyTransfer $currencyTransfer,
        $priceMode
    ): void {
        $netModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        $shipmentMethodTransfer = $shipmentExpenseTransfer->getShipment()->getMethod();

        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            if ($moneyValueTransfer->getCurrency()->getCode() !== $currencyTransfer->getCode()) {
                continue;
            }

            $moneyValueTransfer = $this->shipmentMethodPriceCalculator->applySourcePrices($moneyValueTransfer, $shipmentMethodTransfer);

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
}
