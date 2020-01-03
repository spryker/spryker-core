<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Locker;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\Model\OperationInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface;

class QuoteLocker implements QuoteLockerInterface
{
    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\Cart\Business\Model\OperationInterface
     */
    protected $operation;

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteLockPreResetPluginInterface[]
     */
    protected $quoteLockPreResetPlugins;

    /**
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\Cart\Business\Model\OperationInterface $operation
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteLockPreResetPluginInterface[] $quoteLockPreResetPlugins
     */
    public function __construct(
        CartToQuoteFacadeInterface $quoteFacade,
        OperationInterface $operation,
        array $quoteLockPreResetPlugins
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->operation = $operation;
        $this->quoteLockPreResetPlugins = $quoteLockPreResetPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->executeQuoteLockPreResetPlugins($quoteTransfer);

        $quoteTransfer = $this->quoteFacade->unlockQuote($quoteTransfer);
        $quoteTransfer = $this->operation->reloadItems($quoteTransfer);

        return (new QuoteResponseTransfer())
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeQuoteLockPreResetPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quoteLockPreResetPlugins as $quoteLockPreResetPlugin) {
            $quoteTransfer = $quoteLockPreResetPlugin->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
