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
     * @var \Spryker\Zed\Quote\Communication\Plugin\QuoteHydrationPluginInterface[]
     */
    protected $quoteHydrationPlugins;

    /**
     * @param \Spryker\Zed\Quote\Communication\Plugin\QuoteHydrationPluginInterface[] $quoteHydrationPlugins
     */
    public function __construct(array $quoteHydrationPlugins)
    {
        $this->quoteHydrationPlugins = $quoteHydrationPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function executeHydrationPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quoteHydrationPlugins as $quoteHydrationPlugin) {
            $quoteTransfer = $quoteHydrationPlugin->hydrate($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
