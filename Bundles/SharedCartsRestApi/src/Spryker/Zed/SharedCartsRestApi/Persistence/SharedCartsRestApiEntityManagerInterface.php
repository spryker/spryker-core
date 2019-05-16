<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Persistence;

use Generated\Shared\Transfer\QuoteCompanyUserTransfer;

interface SharedCartsRestApiEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer
     */
    public function saveQuoteCompanyUser(QuoteCompanyUserTransfer $quoteCompanyUserTransfer): QuoteCompanyUserTransfer;
}
