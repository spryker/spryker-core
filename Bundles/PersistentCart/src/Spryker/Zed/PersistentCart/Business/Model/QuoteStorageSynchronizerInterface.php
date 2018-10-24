<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface QuoteStorageSynchronizerInterface
{
    /**
     * @deprecated Use syncStorageQuoteWithStore() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @throws \Spryker\Zed\PersistentCart\Business\Exception\QuoteSynchronizationNotAvailable
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @throws \Spryker\Zed\PersistentCart\Business\Exception\QuoteSynchronizationNotAvailable
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function syncStorageQuoteWithStore(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer;
}
