<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteCompanyUserWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateQuoteCompanyUsers(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function deleteShareRelationsForCompanyUserId(int $idCompanyUser): void;

    /**
     * @param int $idQuote
     * @param int $idCompanyUser
     * @param string $permissionGroupName
     *
     * @return void
     */
    public function shareQuoteWithCompanyUser(int $idQuote, int $idCompanyUser, string $permissionGroupName): void;
}
