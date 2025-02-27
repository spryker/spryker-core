<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Filter;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteFieldsFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string|array<string>>
     */
    public function filterQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array;
}
