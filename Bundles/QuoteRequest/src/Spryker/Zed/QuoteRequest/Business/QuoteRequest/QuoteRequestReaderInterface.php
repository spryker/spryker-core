<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;

interface QuoteRequestReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getCustomerQuoteRequestCollection(CustomerTransfer $customerTransfer): QuoteRequestCollectionTransfer;
}
