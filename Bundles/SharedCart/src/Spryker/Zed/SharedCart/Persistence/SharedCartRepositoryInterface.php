<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;

interface SharedCartRepositoryInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer;

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByCustomer(string $customerReference): PermissionCollectionTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\SpyQuoteEntityTransfer[]
     */
    public function findQuotesByIdCompanyUser(int $idCompanyUser): array;

    /**
     * @param string $customerReference
     *
     * @return array
     */
    public function findShareInformationCustomer($customerReference): array;

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    public function findQuotePermissionGroupList(QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer): array;

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer[]
     */
    public function findQuoteCompanyUserIdCollection(int $idQuote): array;

    /**
     * @param string $customerReference
     *
     * @return string
     */
    public function getCustomerIdByReference(string $customerReference): string;
}
