<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyPermissionEntityTransfer;
use Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;

interface SharedCartEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPermissionEntityTransfer
     */
    public function savePermissionEntity(SpyPermissionEntityTransfer $permissionEntityTransfer): SpyPermissionEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    public function saveQuotePermissionGroupEntity(SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer): SpyQuotePermissionGroupEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return void
     */
    public function saveQuotePermissionGroupToPermissionEntity(
        SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer,
        SpyPermissionEntityTransfer $permissionEntityTransfer
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
}
