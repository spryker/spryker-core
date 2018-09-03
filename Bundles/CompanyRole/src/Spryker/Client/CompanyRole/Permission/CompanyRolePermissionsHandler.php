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
use Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface;

class CompanyRolePermissionsHandler implements CompanyRolePermissionsHandlerInterface
{
    protected const PERMISSION_KEY_GLOSSARY_PREFIX = 'permission.name.';

    /**
     * @var \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToPermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @var \Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface
     */
    protected $companyRoleStub;

    /**
     * @param \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToPermissionClientInterface $permissionClient
     * @param \Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface $companyRoleStub
     */
    public function __construct(
        CompanyRoleToPermissionClientInterface $permissionClient,
        CompanyRoleStubInterface $companyRoleStub
    ) {
        $this->permissionClient = $permissionClient;
        $this->companyRoleStub = $companyRoleStub;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findFilteredCompanyRolePermissionsByIdCompanyRole(
        CompanyRoleTransfer $companyRoleTransfer
    ): PermissionCollectionTransfer {
        $preparedPermissions = new ArrayObject();

        $storedCompanyRolePermissions = $this->companyRoleStub->findCompanyRolePermissions($companyRoleTransfer)->getPermissions();
        $allAvailablePermissions = $this->permissionClient->findAll()->getPermissions();

        $registeredPermissions = $this->permissionClient->getRegisteredPermissions()->getPermissions();
        $infrastructuralPermissionKeys = $this->getInfrastructuralPermissionKeys($registeredPermissions);

        foreach ($allAvailablePermissions as $permissionTransfer) {
            if (in_array($permissionTransfer->getKey(), $infrastructuralPermissionKeys, true)) {
                continue;
            }

            $permissionData = $this->transformPermissionTransferToArray(
                $companyRoleTransfer->getIdCompanyRole(),
                $permissionTransfer,
                $storedCompanyRolePermissions
            );

            $preparedPermissions->append($permissionData);
        }

        return (new PermissionCollectionTransfer())
            ->setPermissions($preparedPermissions);
    }

    /**
     * @param int $idCompanyRole
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $companyRolePermissions
     *
     * @return array
     */
    protected function transformPermissionTransferToArray(
        int $idCompanyRole,
        PermissionTransfer $permissionTransfer,
        ArrayObject $companyRolePermissions
    ): array {
        $permissionData = $permissionTransfer->toArray(false, true);
        $permissionData[CompanyRoleTransfer::ID_COMPANY_ROLE] = null;

        $permissionGlossaryKeyName = static::PERMISSION_KEY_GLOSSARY_PREFIX . $permissionData[PermissionTransfer::KEY];
        $permissionData[PermissionTransfer::KEY] = $permissionGlossaryKeyName;

        foreach ($companyRolePermissions as $companyRolePermission) {
            if ($companyRolePermission->getKey() === $permissionTransfer->getKey()) {
                $permissionData[CompanyRoleTransfer::ID_COMPANY_ROLE] = $idCompanyRole;
                break;
            }
        }

        return $permissionData;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $registeredPermissionTransfers
     *
     * @return string[]
     */
    protected function getInfrastructuralPermissionKeys(ArrayObject $registeredPermissionTransfers): array
    {
        $infrastructuralPermissionKeys = [];

        foreach ($registeredPermissionTransfers as $permissionTransfer) {
            if ($permissionTransfer->getIsInfrastructural()) {
                $infrastructuralPermissionKeys[] = $permissionTransfer->getKey();
            }
        }

        return $infrastructuralPermissionKeys;
    }
}
