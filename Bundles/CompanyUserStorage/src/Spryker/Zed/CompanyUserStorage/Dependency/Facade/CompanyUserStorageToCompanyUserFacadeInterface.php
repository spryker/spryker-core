<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Dependency\Facade;

interface CompanyUserStorageToCompanyUserFacadeInterface
{
    /**
     * @param int[] $companyUserIds
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByIds(array $companyUserIds): array;

    /**
     * @param int[] $companyIds
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCompanyIds(array $companyIds): array;
}
