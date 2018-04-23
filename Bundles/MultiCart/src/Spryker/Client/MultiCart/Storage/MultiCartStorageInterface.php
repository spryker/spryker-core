<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Storage;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MultiCartStorageInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return void
     */
    public function setQuoteCollection(QuoteCollectionTransfer $quoteCollectionTransfer): void;

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer;

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteById(int $idQuote): ?QuoteTransfer;
}
