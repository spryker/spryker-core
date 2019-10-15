<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface CompanyUserStorageRepositoryInterface
{
    /**
     * @deprecated Use getCompanyUserStorageByFilter instead.
     *
     * @param array $companyUserIds
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function findCompanyUserStorageEntities(array $companyUserIds): array;

    /**
     * @deprecated Use getAllCompanyUserStorageByFilter instead.
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function findAllCompanyUserStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function getAllCompanyUserStorageByFilter(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $companyUserIds
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     */
    public function getCompanyUserStorageByFilter(FilterTransfer $filterTransfer, array $companyUserIds): array;
}
