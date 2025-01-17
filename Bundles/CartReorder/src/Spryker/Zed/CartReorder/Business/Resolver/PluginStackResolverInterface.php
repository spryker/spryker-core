<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder\Business\Resolver;

use Generated\Shared\Transfer\QuoteTransfer;

interface PluginStackResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<object>|array<string, list<object>> $pluginStack
     *
     * @return list<object>
     */
    public function resolvePluginStackByQuoteProcessFlowName(QuoteTransfer $quoteTransfer, array $pluginStack): array;
}
