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
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface
     */
    protected $quoteOperationChecker;

    /**
     * @var \Spryker\Shared\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    protected $cartCodePlugins;

    /**
     * @param \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface $calculationClient
     * @param \Spryker\Zed\CartCode\Business\Operation\QuoteOperationCheckerInterface $quoteOperationChecker
     * @param \Spryker\Shared\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[] $cartCodePlugins
     */
    public function __construct(
        CartCodeToCalculationFacadeInterface $calculationClient,
        QuoteOperationCheckerInterface $quoteOperationChecker,
        array $cartCodePlugins = []
    ) {
        $this->calculationFacade = $calculationClient;
        $this->quoteOperationChecker = $quoteOperationChecker;
        $this->cartCodePlugins = $cartCodePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();
        $lockedCartCodeOperationResultTransfer = $this->quoteOperationChecker->checkLockedQuoteResponse($quoteTransfer);
        if ($lockedCartCodeOperationResultTransfer) {
            return $lockedCartCodeOperationResultTransfer;
        }

        $cartCode = $cartCodeRequestTransfer->getCartCode();
        $quoteTransfer = $this->executeCartCodePlugins($quoteTransfer, $cartCode);
        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);

        return $this->processRecalculationResults($quoteTransfer, $cartCode);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeCartCodePlugins(QuoteTransfer $quoteTransfer, string $code): QuoteTransfer
    {
        foreach ($this->cartCodePlugins as $cartCodePlugin) {
            $quoteTransfer = $cartCodePlugin->addCandidate($quoteTransfer, $code);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    protected function processRecalculationResults(QuoteTransfer $quoteTransfer, string $code): CartCodeResponseTransfer
    {
        $cartCodeResponseTransfer = (new CartCodeResponseTransfer())->setIsSuccessful(true);
        $cartCodeResponseTransfer->setQuote($quoteTransfer);

        foreach ($this->cartCodePlugins as $cartCodePlugin) {
            $messageTransfer = $cartCodePlugin->getOperationResponseMessage($quoteTransfer, $code);

            if ($messageTransfer) {
                $cartCodeResponseTransfer->addMessage($messageTransfer);
            }

            if ($messageTransfer->getType() === static::MESSAGE_TYPE_ERROR) {
                $cartCodeResponseTransfer->setIsSuccessful(false);
            }
        }

        return $cartCodeResponseTransfer;
    }
}
