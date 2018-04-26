<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor;

use Generated\Shared\Transfer\QuoteResponseTransfer;

class QuoteUpdatePluginExecutor implements QuoteUpdatePluginExecutorInterface
{
    /**
     * @var array|\Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface[]
     */
    protected $quoteUpdatePlugins;

    /**
     * @param \Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface[] $quoteUpdatePlugins
     */
    public function __construct(array $quoteUpdatePlugins)
    {
        $this->quoteUpdatePlugins = $quoteUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function executePlugins(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        foreach ($this->quoteUpdatePlugins as $quoteUpdatePlugin) {
            $quoteResponseTransfer = $quoteUpdatePlugin->processResponse($quoteResponseTransfer);
        }

        return $quoteResponseTransfer;
    }
}
