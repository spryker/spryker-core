<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteLocker implements QuoteLockerInterface
{
    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteUnlockPreCheckPluginInterface[]
     */
    protected $quoteUnlockPreCheckPlugins;

    /**
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteUnlockPreCheckPluginInterface[] $quoteUnlockPreCheckPlugins
     */
    public function __construct(array $quoteUnlockPreCheckPlugins)
    {
        $this->quoteUnlockPreCheckPlugins = $quoteUnlockPreCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lock(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer->setIsLocked(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function unlock(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$this->canUnlockQuote($quoteTransfer)) {
            return $quoteTransfer;
        }

        return $quoteTransfer->setIsLocked(false);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function canUnlockQuote(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($this->quoteUnlockPreCheckPlugins as $quoteUnlockPreCheckPlugin) {
            if (!$quoteUnlockPreCheckPlugin->can($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
