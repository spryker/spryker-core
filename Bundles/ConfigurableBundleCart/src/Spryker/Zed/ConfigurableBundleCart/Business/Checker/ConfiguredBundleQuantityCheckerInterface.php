<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business\Checker;

use Generated\Shared\Transfer\QuoteTransfer;

interface ConfiguredBundleQuantityCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkConfiguredBundleQuantity(QuoteTransfer $quoteTransfer): bool;
}
