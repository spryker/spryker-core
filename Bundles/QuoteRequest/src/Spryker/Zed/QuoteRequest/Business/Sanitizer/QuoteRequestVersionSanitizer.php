<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Sanitizer;

use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationFacadeInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartFacadeInterface;

class QuoteRequestVersionSanitizer implements QuoteRequestVersionSanitizerInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationFacadeInterface $calculationFacade
     */
    public function __construct(
        QuoteRequestToCartFacadeInterface $cartFacade,
        QuoteRequestToCalculationFacadeInterface $calculationFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function cleanUpQuoteRequestVersionQuote(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestVersionTransfer
    {
        $quoteTransfer = $this->clearSourcePrices($quoteRequestVersionTransfer->getQuote());

        $quoteRequestVersionTransfer->setQuote($quoteTransfer);

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function reloadQuoteRequestVersionItems(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestVersionTransfer
    {
        if ($quoteRequestVersionTransfer->getQuote()->getItems()->count()) {
            $quoteRequestVersionTransfer->setQuote($this->cartFacade->reloadItems($quoteRequestVersionTransfer->getQuote()));
        }

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function recalculateQuoteRequestVersionQuote(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestVersionTransfer->requireQuote()
            ->getQuote()
            ->requireItems();

        $recalculateQuoteTransfer = $this->calculationFacade->recalculateQuote($quoteRequestVersionTransfer->getQuote());
        $quoteRequestVersionTransfer->setQuote($recalculateQuoteTransfer);

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeQuoteRequest(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer
            ->setQuoteRequestVersionReference(null)
            ->setQuoteRequestReference(null);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function clearSourcePrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = $this->clearItemSourcePrices($quoteTransfer);
        $quoteTransfer = $this->clearItemShipmentMethodSourcePrices($quoteTransfer);
        $quoteTransfer = $this->clearSingleShipmentMethodSourcePrices($quoteTransfer);
        $quoteTransfer = $this->clearShipmentExpensesSourcePrices($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function clearShipmentExpensesSourcePrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentTransfer = $expenseTransfer->getShipment();

            if ($shipmentTransfer && $shipmentTransfer->getMethod()) {
                $shipmentTransfer->getMethod()->setSourcePrice(null);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function clearItemSourcePrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setSourceUnitGrossPrice(null);
            $itemTransfer->setSourceUnitNetPrice(null);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function clearItemShipmentMethodSourcePrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() && $itemTransfer->getShipment()->getMethod()) {
                $itemTransfer->getShipment()->getMethod()->setSourcePrice(null);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed without replacement. BC-reason only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function clearSingleShipmentMethodSourcePrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getShipment() && $quoteTransfer->getShipment()->getMethod()) {
            $quoteTransfer->getShipment()->getMethod()->setSourcePrice(null);
        }

        return $quoteTransfer;
    }
}
