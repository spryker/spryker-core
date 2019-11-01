<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business\Operation;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface;

class CodeDeleter implements CodeDeleterInterface
{
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
     * @param array $cartCodePlugins
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        $lockedCartCodeOperationResultTransfer = $this->quoteOperationChecker->checkLockedQuoteResponse($quoteTransfer);
        if ($lockedCartCodeOperationResultTransfer) {
            return $lockedCartCodeOperationResultTransfer;
        }

        $quoteTransfer = $this->executePlugins($quoteTransfer, $code);
        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);

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
        foreach ($this->cartCodePlugins as $cartCodePlugin) {
            $quoteTransfer = $cartCodePlugin->removeCode($quoteTransfer, $code);
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

        foreach ($this->cartCodePlugins as $cartCodePlugin) {
            $messageTransfer = $cartCodePlugin->getOperationResponseMessage($quoteTransfer, $code);

            if ($messageTransfer) {
                $cartCodeOperationResultTransfer->addMessage($messageTransfer);
            }
        }

        return $cartCodeOperationResultTransfer;
    }
}
