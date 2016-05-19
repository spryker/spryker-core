<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CollectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);
}
