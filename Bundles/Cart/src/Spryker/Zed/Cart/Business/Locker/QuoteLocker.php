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
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\QuotePreUnlockPluginInterface[]
     */
    protected $quotePreUnlockPlugins;

    /**
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\Cart\Business\Model\OperationInterface $operation
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\QuotePreUnlockPluginInterface[] $quotePreUnlockPlugins
     */
    public function __construct(
        CartToQuoteFacadeInterface $quoteFacade,
        OperationInterface $operation,
        array $quotePreUnlockPlugins
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->operation = $operation;
        $this->quotePreUnlockPlugins = $quotePreUnlockPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function unlock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->executeQuotePreUnlockPlugins($quoteTransfer);

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
    protected function executeQuotePreUnlockPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quotePreUnlockPlugins as $quotePreUnlockPlugin) {
            $quoteTransfer = $quotePreUnlockPlugin->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
