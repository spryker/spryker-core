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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setSourceUnitGrossPrice(null);
            $itemTransfer->setSourceUnitNetPrice(null);
        }

        return $quoteTransfer;
    }
}
