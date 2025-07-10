<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Saver;

use Generated\Shared\Transfer\QuoteTransfer;

interface SalesOrderAmendmentQuoteSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveNotExistingSalesOrderAmendmentQuote(QuoteTransfer $quoteTransfer): void;
}
