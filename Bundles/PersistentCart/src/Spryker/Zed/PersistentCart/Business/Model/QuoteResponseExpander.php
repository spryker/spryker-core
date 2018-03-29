<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;

class QuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteResponseExpanderPluginInterface[]
     */
    protected $quoteResponseExpanderPlugins;

    /**
     * @param \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteResponseExpanderPluginInterface[] $quoteResponseExpanderPlugins
     */
    public function __construct($quoteResponseExpanderPlugins)
    {
        $this->quoteResponseExpanderPlugins = $quoteResponseExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        foreach ($this->quoteResponseExpanderPlugins as $quoteResponseExpanderPlugin) {
            $quoteResponseTransfer = $quoteResponseExpanderPlugin->expand($quoteResponseTransfer);
        }

        return $quoteResponseTransfer;
    }
}
