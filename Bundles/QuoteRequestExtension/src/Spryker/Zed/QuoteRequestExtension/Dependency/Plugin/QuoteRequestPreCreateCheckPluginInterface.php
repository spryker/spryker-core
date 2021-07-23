<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestPreCreateCheckPluginInterface
{
    /**
     * Specification:
     * - Returns true if quoteRequest is applicable for quote request creation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteRequestTransfer $quoteRequestTransfer): bool;
}
