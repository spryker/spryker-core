<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole\Permission;

use ArrayObject;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToPermissionClientInterface;

class CompanyRolePermissionsHandler implements CompanyRolePermissionsHandlerInterface
{
    /**
     * @var \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToPermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @param \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToPermissionClientInterface $permissionClient
     */
    public function __construct(CompanyRoleToPermissionClientInterface $permissionClient)
    {
        $this->permissionClient = $permissionClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $companyRolePermissions
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function filterCompanyRolePermissions(
        CompanyRoleTransfer $companyRoleTransfer,
        PermissionCollectionTransfer $companyRolePermissions
    ): PermissionCollectionTransfer {
        $preparedPermissions = new ArrayObject();

        $companyRolePermissionTransfers = $companyRolePermissions->getPermissions();

        $allPermissionTransfers = $this->permissionClient->findAll()
            ->getPermissions();

        $registeredPermissionTransfers = $this->permissionClient->getRegisteredPermissions()
            ->getPermissions();

        $awareConfigurationPermissionKeys = $this->getAwareConfigurationPermissionKeys($registeredPermissionTransfers);

        foreach ($allPermissionTransfers as $permissionTransfer) {
            if (in_array($permissionTransfer->getKey(), $awareConfigurationPermissionKeys, true)) {
                continue;
            }

            $permissionData = $this->transformCompanyRolePermissionTransferToArray(
                $companyRolePermissionTransfers,
                $permissionTransfer,
                $companyRoleTransfer->getIdCompanyRole()
            );

            $preparedPermissions->append($permissionData);
        }

        return (new PermissionCollectionTransfer())
            ->setPermissions($preparedPermissions);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $companyRolePermissionTransfers
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     * @param int $idCompanyRole
     *
     * @return array
     */
    protected function transformCompanyRolePermissionTransferToArray(
        ArrayObject $companyRolePermissionTransfers,
        PermissionTransfer $permissionTransfer,
        int $idCompanyRole
    ): array {
        $permissionAsArray = $permissionTransfer->toArray(false, true);
        $permissionAsArray[CompanyRoleTransfer::ID_COMPANY_ROLE] = null;

        foreach ($companyRolePermissionTransfers as $rolePermission) {
            if ($rolePermission->getKey() === $permissionTransfer->getKey()) {
                $permissionAsArray[CompanyRoleTransfer::ID_COMPANY_ROLE] = $idCompanyRole;
                break;
            }
        }

        return $permissionAsArray;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $registeredPermissionTransfers
     *
     * @return string[]
     */
    protected function getAwareConfigurationPermissionKeys(ArrayObject $registeredPermissionTransfers): array
    {
        $awareConfigurationPermissionKeys = [];

        foreach ($registeredPermissionTransfers as $permissionTransfer) {
            if ($permissionTransfer->getIsAwareConfiguration()) {
                $awareConfigurationPermissionKeys[] = $permissionTransfer->getKey();
            }
        }

        return $awareConfigurationPermissionKeys;
    }
}
