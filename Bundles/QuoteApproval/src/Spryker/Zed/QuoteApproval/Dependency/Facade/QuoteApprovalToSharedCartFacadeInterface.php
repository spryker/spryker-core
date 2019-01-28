<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalToSharedCartFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function deleteShareForQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * @param int $idQuote
     * @param int $idCompanyUser
     * @param string $permissionGroupName
     *
     * @return void
     */
    public function shareQuoteWithCompanyUser(int $idQuote, int $idCompanyUser, string $permissionGroupName): void;
}
