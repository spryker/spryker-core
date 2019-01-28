<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Persistence;

interface CompanyUserStorageRepositoryInterface
{
    /**
     * @param array $companyUserIds
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findCompanyUserStorageTransfers(array $companyUserIds): array;

    /**
     * @param array $companyUserIds
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer[]
     */
    public function findCompanyUserStorageEntities(array $companyUserIds): array;

    /**
     * @return \Generated\Shared\Transfer\SpyCompanyUserStorageEntityTransfer[]
     */
    public function findAllCompanyUserStorageEntities(): array;
}
