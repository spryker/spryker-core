<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteMergeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteMergerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteMergeTransfer $quoteMergeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function merge(QuoteMergeTransfer $quoteMergeTransfer): QuoteResponseTransfer;
}
