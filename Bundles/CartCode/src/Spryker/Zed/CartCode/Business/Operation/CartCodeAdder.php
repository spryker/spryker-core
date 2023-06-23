<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business\Operation;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    /**
     * @var \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface
     */
    protected CartCodeToCalculationFacadeInterface $calculationFacade;

    /**
     * @var \Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface
     */
    protected QuoteOperationCheckerInterface $quoteOperationChecker;

    /**
     * @var \Spryker\Zed\CartCode\Business\Operation\RecalculationResultProcessorInterface
     */
    protected RecalculationResultProcessorInterface $recalculationResultProcessor;

    /**
     * @var list<\Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface>
     */
    protected array $cartCodePlugins;

    /**
     * @var list<\Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePostAddPluginInterface>
     */
    protected array $cartCodePostAddPlugins;

    /**
     * @param \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface $calculationClient
     * @param \Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface $quoteOperationChecker
     * @param \Spryker\Zed\CartCode\Business\Operation\RecalculationResultProcessorInterface $recalculationResultProcessor
     * @param list<\Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface> $cartCodePlugins
     * @param list<\Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePostAddPluginInterface> $cartCodePostAddPlugins
     */
    public function __construct(
        CartCodeToCalculationFacadeInterface $calculationClient,
        QuoteOperationCheckerInterface $quoteOperationChecker,
        RecalculationResultProcessorInterface $recalculationResultProcessor,
        array $cartCodePlugins,
        array $cartCodePostAddPlugins
    ) {
        $this->calculationFacade = $calculationClient;
        $this->quoteOperationChecker = $quoteOperationChecker;
        $this->recalculationResultProcessor = $recalculationResultProcessor;
        $this->cartCodePlugins = $cartCodePlugins;
        $this->cartCodePostAddPlugins = $cartCodePostAddPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $lockedCartCodeResponseTransfer = $this->quoteOperationChecker
            ->checkLockedQuoteResponse($cartCodeRequestTransfer->getQuote());

        if ($lockedCartCodeResponseTransfer) {
            return $lockedCartCodeResponseTransfer;
        }

        $quoteTransfer = $this->executeCartCodePlugins($cartCodeRequestTransfer);
        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);
        $cartCodeResponseTransfer = $this->executeCartCodePostAddPlugins($cartCodeRequestTransfer->setQuote($quoteTransfer));

        if (!$cartCodeResponseTransfer->getIsSuccessful()) {
            return $cartCodeResponseTransfer;
        }

        return $this->recalculationResultProcessor->processRecalculationResults($cartCodeRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeCartCodePlugins(CartCodeRequestTransfer $cartCodeRequestTransfer): QuoteTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();

        foreach ($this->cartCodePlugins as $cartCodePlugin) {
            $quoteTransfer = $cartCodePlugin->addCartCode($quoteTransfer, $cartCodeRequestTransfer->getCartCode());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    protected function executeCartCodePostAddPlugins(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $cartCodeResponseTransfer = (new CartCodeResponseTransfer())->setIsSuccessful(true);

        foreach ($this->cartCodePostAddPlugins as $cartCodePostAddPlugin) {
            $cartCodeResponseTransfer = $cartCodePostAddPlugin->execute($cartCodeRequestTransfer);

            if (!$cartCodeResponseTransfer->getIsSuccessful()) {
                return $cartCodeResponseTransfer;
            }
        }

        return $cartCodeResponseTransfer;
    }
}
