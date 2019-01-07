<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout\PluginExecutor;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteProceedCheckoutCheckPluginExecutor implements QuoteProceedCheckoutCheckPluginExecutorInterface
{
    /**
     * @var \Spryker\Client\Checkout\Plugin\QuoteProceedCheckoutCheckPluginInterface[]
     */
    protected $quoteProccedCheckoutCheckPlugins;

    /**
     * @param \Spryker\Client\Checkout\Plugin\QuoteProceedCheckoutCheckPluginInterface[] $quoteProccedCheckoutCheckPlugins
     */
    public function __construct(array $quoteProccedCheckoutCheckPlugins)
    {
        $this->quoteProccedCheckoutCheckPlugins = $quoteProccedCheckoutCheckPlugins;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function execute(QuoteTransfer $quoteTransfer): bool
    {
        if (empty($this->quoteProccedCheckoutCheckPlugins)) {
            return true;
        }

        foreach ($this->quoteProccedCheckoutCheckPlugins as $plugin) {
            if (!$plugin->can($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
