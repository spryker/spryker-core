<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class QuotePluginExecutor implements QuotePluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\Quote\Dependency\Plugin\QuotePreSavePluginInterface[]
     */
    protected $quotePreSavePlugins;

    /**
     * @param \Spryker\Zed\Quote\Dependency\Plugin\QuotePreSavePluginInterface[] $quotePreSavePlugins
     */
    public function __construct(array $quotePreSavePlugins = [])
    {
        $this->quotePreSavePlugins = $quotePreSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function executeQuotePreSavePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quotePreSavePlugins as $quotePreSavePlugin) {
            $quoteTransfer = $quotePreSavePlugin->preSave($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
