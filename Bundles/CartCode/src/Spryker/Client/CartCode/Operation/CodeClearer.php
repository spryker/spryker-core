<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode\Operation;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface;

class CodeClearer implements CodeClearerInterface
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
     * @var \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    protected $cartCodePlugins;

    /**
     * @param \Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface $calculationClient
     * @param \Spryker\Client\CartCode\Operation\QuoteOperationCheckerInterface $quoteOperationChecker
     * @param \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[] $cartCodePlugins
     */
    public function __construct(
        CartCodeToCalculationClientInterface $calculationClient,
        QuoteOperationCheckerInterface $quoteOperationChecker,
        array $cartCodePlugins = []
    ) {
        $this->calculationClient = $calculationClient;
        $this->quoteOperationChecker = $quoteOperationChecker;
        $this->cartCodePlugins = $cartCodePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function clearAllCodes(QuoteTransfer $quoteTransfer): CartCodeOperationResultTransfer
    {
        $lockedCartCodeOperationResultTransfer = $this->quoteOperationChecker->checkLockedQuoteResponse($quoteTransfer);
        if ($lockedCartCodeOperationResultTransfer) {
            return $lockedCartCodeOperationResultTransfer;
        }

        $quoteTransfer = $this->executePlugins($quoteTransfer);
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        return $this->createResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->cartCodePlugins as $cartCodePlugin) {
            $quoteTransfer = $cartCodePlugin->clearAllCodes($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    protected function createResponse(QuoteTransfer $quoteTransfer): CartCodeOperationResultTransfer
    {
        $cartCodeOperationResultTransfer = new CartCodeOperationResultTransfer();
        $cartCodeOperationResultTransfer->setQuote($quoteTransfer);

        return $cartCodeOperationResultTransfer;
    }
}
