<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

interface CartToCalculationInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $executeQuotePlugins
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer, bool $executeQuotePlugins = true);
}
