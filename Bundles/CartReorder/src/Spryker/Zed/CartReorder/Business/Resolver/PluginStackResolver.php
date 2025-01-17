<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder\Business\Resolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartReorder\CartReorderConfig;

class PluginStackResolver implements PluginStackResolverInterface
{
    /**
     * @var \Spryker\Zed\CartReorder\CartReorderConfig
     */
    protected CartReorderConfig $cartReorderConfig;

    /**
     * @param \Spryker\Zed\CartReorder\CartReorderConfig $cartReorderConfig
     */
    public function __construct(CartReorderConfig $cartReorderConfig)
    {
        $this->cartReorderConfig = $cartReorderConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<object>|array<string, list<object>> $pluginStack
     *
     * @return list<object>
     */
    public function resolvePluginStackByQuoteProcessFlowName(QuoteTransfer $quoteTransfer, array $pluginStack): array
    {
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()
            ? $quoteTransfer->getQuoteProcessFlowOrFail()->getNameOrFail()
            : null;

        if ($quoteProcessFlowName && isset($pluginStack[$quoteProcessFlowName])) {
            /** @phpstan-ignore-next-line */
            return $pluginStack[$quoteProcessFlowName];
        }

        /** @phpstan-ignore-next-line */
        return $pluginStack[$this->cartReorderConfig->getDefaultQuoteProcessFlowName()] ?? $pluginStack;
    }
}
