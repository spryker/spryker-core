<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Dependency\Facade;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface QuoteApprovalToCompanyRoleFacadeInterface
{
    /**
     * @param string $permissionKey
     * @param int|null $idCompany
     *
     * @return array<int>
     */
    public function getCompanyUserIdsByPermissionKey(string $permissionKey, ?int $idCompany = null): array;

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer;
}
