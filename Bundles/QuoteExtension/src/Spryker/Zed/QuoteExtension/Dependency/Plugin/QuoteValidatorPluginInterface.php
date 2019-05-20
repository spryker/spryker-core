<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;

interface QuoteValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates quote.
     * - Returns error message when validation failed.
     * - Returns empty transfer if validation success.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer;
}
