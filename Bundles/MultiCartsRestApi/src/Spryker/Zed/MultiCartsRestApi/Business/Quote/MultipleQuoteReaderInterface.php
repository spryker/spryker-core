<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;

interface MultipleQuoteReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getCustomerQuoteCollection(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): QuoteCollectionResponseTransfer;
}
