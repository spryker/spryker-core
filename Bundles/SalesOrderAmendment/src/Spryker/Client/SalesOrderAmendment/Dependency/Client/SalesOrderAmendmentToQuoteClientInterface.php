<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface SalesOrderAmendmentToQuoteClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;

    /**
     * @return void
     */
    public function clearQuote(): void;
}
