<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer;

use Generated\Shared\Transfer\QuoteTransfer;

interface OmsEventTriggererInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerStartOrderAmendmentEvent(QuoteTransfer $quoteTransfer): ?array;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerCancelOrderAmendmentEvent(QuoteTransfer $quoteTransfer): ?array;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerFinishSalesOrderAmendmentEvent(QuoteTransfer $quoteTransfer): ?array;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerStartOrderAmendmentDraftEvent(QuoteTransfer $quoteTransfer): ?array;
}
