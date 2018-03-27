<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteMergerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteMergeRequestTransfer $quoteMergeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function merge(QuoteMergeRequestTransfer $quoteMergeRequestTransfer): QuoteTransfer;
}
