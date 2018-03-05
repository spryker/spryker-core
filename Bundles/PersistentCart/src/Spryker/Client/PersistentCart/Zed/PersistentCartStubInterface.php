<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Zed;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PersistentCartStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function addItem(PersistentCartChangeTransfer $persistentCartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function removeItem(PersistentCartChangeTransfer $persistentCartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer);

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer);

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteTransfer;
}
