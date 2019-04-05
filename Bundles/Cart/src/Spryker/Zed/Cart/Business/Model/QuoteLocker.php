<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class QuoteLocker implements QuoteLockerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\Cart\Business\Model\OperationInterface
     */
    protected $operation;

    /**
     * @var array|\Spryker\Zed\CartExtension\Dependency\Plugin\QuoteBeforeUnlockPluginInterface[]
     */
    protected $quoteBeforeUnlockPlugins;

    /**
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\Cart\Business\Model\OperationInterface $operation
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteBeforeUnlockPluginInterface[] $quoteBeforeUnlockPlugins
     */
    public function __construct(
        CartToQuoteFacadeInterface $quoteFacade,
        OperationInterface $operation,
        array $quoteBeforeUnlockPlugins
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->operation = $operation;
        $this->quoteBeforeUnlockPlugins = $quoteBeforeUnlockPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function unlock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            return $this->executeUnlockTransaction($quoteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function executeUnlockTransaction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        foreach ($this->quoteBeforeUnlockPlugins as $quoteBeforeUnlockPlugin) {
            $quoteTransfer = $quoteBeforeUnlockPlugin->execute($quoteTransfer);
        }

        $quoteTransfer = $this->quoteFacade->unlockQuote($quoteTransfer);
        $quoteTransfer = $this->operation->reloadItems($quoteTransfer);

        return (new QuoteResponseTransfer())
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);
    }
}
