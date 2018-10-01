<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer;

interface SharedCartEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function savePermission(PermissionTransfer $permissionTransfer): PermissionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer
     */
    public function saveQuotePermissionGroup(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): QuotePermissionGroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function saveQuotePermissionGroupToPermission(
        QuotePermissionGroupTransfer $quotePermissionGroupTransfer,
        PermissionTransfer $permissionTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer $companyUserEntityTransfer
     *
     * @return void
     */
    public function saveQuoteCompanyUser(SpyQuoteCompanyUserEntityTransfer $companyUserEntityTransfer): void;

    /**
     * @param int $idQuoteCompanyUser
     *
     * @return void
     */
    public function deleteQuoteCompanyUser(int $idQuoteCompanyUser): void;

    /**
     * @param int $idCompanyUser
     * @param int $idQuote
     *
     * @return void
     */
    public function setQuoteDefault(int $idCompanyUser, int $idQuote): void;

    /**
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function resetQuoteDefaultFlagByCustomer(int $idCompanyUser): void;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function deleteQuoteCompanyUserByQuote(QuoteTransfer $quoteTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     *
     * @return void
     */
    public function updateCompanyUserQuotePermissionGroup(ShareDetailTransfer $shareDetailTransfer): void;
}
