<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode\Operation;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface;

class CodeRemover implements CodeRemoverInterface
{
    /**
     * @var \Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \Spryker\Client\CartCode\Operation\QuoteOperationCheckerInterface
     */
    protected $quoteOperationChecker;

    /**
     * @var \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface[]
     */
    protected $cartCodeHandlerPlugins;

    /**
     * @param \Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface $calculationClient
     * @param \Spryker\Client\CartCode\Operation\QuoteOperationCheckerInterface $quoteOperationChecker
     * @param \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface[] $cartCodeHandlerPlugins
     */
    public function __construct(
        CartCodeToCalculationClientInterface $calculationClient,
        QuoteOperationCheckerInterface $quoteOperationChecker,
        array $cartCodeHandlerPlugins = []
    ) {
        $this->calculationClient = $calculationClient;
        $this->quoteOperationChecker = $quoteOperationChecker;
        $this->cartCodeHandlerPlugins = $cartCodeHandlerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function remove(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        $lockedCartCodeOperationResultTransfer = $this->quoteOperationChecker->checkLockedQuoteResponse($quoteTransfer);
        if ($lockedCartCodeOperationResultTransfer) {
            return $lockedCartCodeOperationResultTransfer;
        }

        $quoteTransfer = $this->executePlugins($quoteTransfer, $code);
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        return $this->processRecalculationResults($quoteTransfer, $code);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePlugins(QuoteTransfer $quoteTransfer, string $code): QuoteTransfer
    {
        foreach ($this->cartCodeHandlerPlugins as $cartCodeHandlerPlugin) {
            $quoteTransfer = $cartCodeHandlerPlugin->removeCode($quoteTransfer, $code);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    protected function processRecalculationResults(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        $cartCodeOperationResultTransfer = new CartCodeOperationResultTransfer();
        $cartCodeOperationResultTransfer->setQuote($quoteTransfer);

        foreach ($this->cartCodeHandlerPlugins as $cartCodeHandlerPlugin) {
            $cartCodeOperationMessageTransfer = $cartCodeHandlerPlugin->getCartCodeOperationResult($quoteTransfer, $code);

            $cartCodeOperationResultTransfer->addMessage($cartCodeOperationMessageTransfer);
        }

        return $cartCodeOperationResultTransfer;
    }
}
