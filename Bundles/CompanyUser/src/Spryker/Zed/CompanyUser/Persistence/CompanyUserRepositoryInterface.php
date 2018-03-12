<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserRepositoryInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer;

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): CompanyUserCollectionTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findInitialCompanyUserByCompanyId(int $idCompany): ?CompanyUserTransfer;
}
