<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\PersistentItemReplaceTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface CartItemOperationInterface
{
    /**
     * @param \Generated\Shared\Transfer\PersistentItemReplaceTransfer $persistentItemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceItem(PersistentItemReplaceTransfer $persistentItemReplaceTransfer): QuoteResponseTransfer;
}
