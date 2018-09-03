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

        $allPermissions = $this->permissionClient->findAll()->getPermissions();
        $storedCompanyRolePermissions = $this->companyRoleStub->findCompanyRolePermissions($companyRoleTransfer)
            ->getPermissions();

        $filteredPermissions = $this->filterUnassignedPermissions($allPermissions, $storedCompanyRolePermissions);
        $filteredPermissions = $this->getFilteredPermissionKeyIndexes($filteredPermissions);

        foreach ($filteredPermissions as $filteredPermissionTransfer) {
            $permissionData = $this->transformPermissionTransferToArray(
                $companyRoleTransfer->getIdCompanyRole(),
                $filteredPermissions[$filteredPermissionTransfer->getKey()]
            );

            $preparedPermissions->append($permissionData);
        }

        return (new PermissionCollectionTransfer())
            ->setPermissions($preparedPermissions);
    }

    /**
     * @param int $idCompanyRole
     * @param \Generated\Shared\Transfer\PermissionTransfer $storedPermissionTransfer
     *
     * @return array
     */
    protected function transformPermissionTransferToArray(
        int $idCompanyRole,
        PermissionTransfer $storedPermissionTransfer
    ): array {
        $permissionData = $storedPermissionTransfer->toArray(false, true);

        $permissionGlossaryKeyName = static::PERMISSION_KEY_GLOSSARY_PREFIX . $permissionData[PermissionTransfer::KEY];
        $permissionData[PermissionTransfer::KEY] = $permissionGlossaryKeyName;
        $permissionData[CompanyRoleTransfer::ID_COMPANY_ROLE] = $idCompanyRole;

        return $permissionData;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $allPermissions
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $storedCompanyRolePermissions
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected function filterUnassignedPermissions(
        ArrayObject $allPermissions,
        ArrayObject $storedCompanyRolePermissions
    ): ArrayObject {
        $commonPermissions = new ArrayObject();
        foreach ($allPermissions as $permissionTransfer) {
            foreach ($storedCompanyRolePermissions as $storedCompanyRolePermission) {
                if ($storedCompanyRolePermission->getKey() === $permissionTransfer->getKey()) {
                    $commonPermissions[] = $permissionTransfer;
                }
            }
        }

        return $commonPermissions;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $allPermissions
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected function getFilteredPermissionKeyIndexes(ArrayObject $allPermissions): array
    {
        $registeredPermissions = $this->permissionClient->getRegisteredPermissions()->getPermissions();
        $infrastructuralPermissionKeys = $this->getInfrastructuralPermissionKeys($registeredPermissions);

        $filteredPermissions = [];
        foreach ($allPermissions as $permissionTransfer) {
            if (in_array($permissionTransfer->getKey(), $infrastructuralPermissionKeys, true)) {
                continue;
            }

            $filteredPermissions[$permissionTransfer->getKey()] = $permissionTransfer;
        }

        return $filteredPermissions;
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
