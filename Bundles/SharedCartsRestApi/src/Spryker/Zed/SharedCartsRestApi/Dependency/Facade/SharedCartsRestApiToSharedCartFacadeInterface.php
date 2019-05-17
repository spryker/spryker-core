<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer;

interface SharedCartsRestApiToSharedCartFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailsByIdQuote(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer $sharedQuoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getCustomerSharedQuoteCollection(SharedQuoteCriteriaFilterTransfer $sharedQuoteCriteriaFilterTransfer): QuoteCollectionTransfer;
}
