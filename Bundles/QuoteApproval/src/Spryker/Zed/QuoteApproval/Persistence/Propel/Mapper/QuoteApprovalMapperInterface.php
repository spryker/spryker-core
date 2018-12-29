<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval;

interface QuoteApprovalMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval $quoteApprovalEntity
     *
     * @return \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval
     */
    public function mapQuoteApprovalTransferToEntity(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        SpyQuoteApproval $quoteApprovalEntity
    ): SpyQuoteApproval;
}
