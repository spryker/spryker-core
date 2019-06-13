<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteLocker implements QuoteLockerInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\QuoteApprovalExtension\Dependency\Plugin\QuoteApprovalUnlockPreCheckPluginInterface[]
     */
    protected $quoteApprovalUnlockPreCheckPlugins;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApprovalExtension\Dependency\Plugin\QuoteApprovalUnlockPreCheckPluginInterface[] $quoteApprovalUnlockPreCheckPlugins
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        array $quoteApprovalUnlockPreCheckPlugins
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteApprovalUnlockPreCheckPlugins = $quoteApprovalUnlockPreCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer = $this->quoteFacade->lockQuote($quoteTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function unlockQuote(QuoteTransfer $quoteTransfer): void
    {
        if (!$this->executeQuoteApprovalUnlockPreCheckPlugins($quoteTransfer)) {
            return;
        }

        $quoteTransfer = $this->quoteFacade->unlockQuote($quoteTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeQuoteApprovalUnlockPreCheckPlugins(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($this->quoteApprovalUnlockPreCheckPlugins as $quoteApprovalUnlockPreCheckPlugin) {
            if (!$quoteApprovalUnlockPreCheckPlugin->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
