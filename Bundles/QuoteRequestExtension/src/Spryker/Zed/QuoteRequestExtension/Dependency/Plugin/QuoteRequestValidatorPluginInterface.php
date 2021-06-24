<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

/**
 * Use this plugin to validate the quote request before creating or updating.
 */
interface QuoteRequestValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates a quote request.
     * - Returns "isSuccessful=true" on success validation.
     * - Returns "isSuccessful=false" with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function validate(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;
}
