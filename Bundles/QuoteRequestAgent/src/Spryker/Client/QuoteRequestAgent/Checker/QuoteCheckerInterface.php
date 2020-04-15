<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent\Checker;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isEditableQuoteRequestVersion(QuoteTransfer $quoteTransfer): bool;
}
