<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Dependency\Facade;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface PersistentCartShareToQuoteFacadeInterface
{
    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteById(int $idQuote): QuoteResponseTransfer;
}
