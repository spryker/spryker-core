<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business\Model;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;

class CompanyRolePermissionReader implements CompanyRolePermissionReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface $repository
     */
    public function __construct(
        CompanyRoleRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getCompanyRolePermissions(
        CompanyRoleTransfer $companyRoleTransfer
    ): PermissionCollectionTransfer {
        $companyRoleTransfer->requireIdCompanyRole();

        return $this->repository->findCompanyRolePermissions($companyRoleTransfer->getIdCompanyRole());
    }
}
