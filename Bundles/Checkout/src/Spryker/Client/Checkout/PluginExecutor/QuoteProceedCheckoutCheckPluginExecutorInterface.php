<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout\PluginExecutor;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteProceedCheckoutCheckPluginExecutorInterface
{
    /**
     * Executes provided plugins, returns false if at least one plugin returns false, returns true otherwise.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function execute(QuoteTransfer $quoteTransfer): bool;
}
