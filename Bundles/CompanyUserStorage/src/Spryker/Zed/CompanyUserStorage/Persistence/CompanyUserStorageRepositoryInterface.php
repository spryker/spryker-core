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
     * @param array $companyUserIds
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     *@deprecated Use `CompanyUserStorageRepositoryInterface::getCompanyUserStorageCollectionByFilter()` instead.
     *
     * @see \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface::getCompanyUserStorageCollectionByFilterAndCompanyUserIds()
     *
     */
    public function findCompanyUserStorageEntities(array $companyUserIds): array;

    /**
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[]
     *@see \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface::getCompanyUserStorageCollectionByFilterAndCompanyUserIds()
     *
     * @deprecated Use `CompanyUserStorageRepositoryInterface::getCompanyUserStorageCollectionByFilter()` instead.
     *
     */
    public function findAllCompanyUserStorageEntities(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $companyUserIds
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer[]
     */
    public function getCompanyUserStorageCollectionByFilterAndCompanyUserIds(FilterTransfer $filterTransfer, array $companyUserIds): array;
}
