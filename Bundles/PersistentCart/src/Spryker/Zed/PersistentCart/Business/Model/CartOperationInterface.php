<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartOperationInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer);

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer);

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer);
}
