<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface DatabaseStrategyAvailabilityCheckPluginInterface
{
    /**
     * Specification:
     * - Checks database strategy availability.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isAllowed(QuoteTransfer $quoteTransfer): bool;
}
