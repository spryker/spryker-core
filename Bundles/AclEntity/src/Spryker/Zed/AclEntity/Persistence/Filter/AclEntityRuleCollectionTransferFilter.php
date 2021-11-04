<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Filter;

use ArrayObject;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Shared\AclEntity\AclEntityConstants;

class AclEntityRuleCollectionTransferFilter implements AclEntityRuleCollectionTransferFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $scope
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByScope(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $scope
    ): AclEntityRuleCollectionTransfer {
        $filteredAclEntityRuleTransfers = array_filter(
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
            function (AclEntityRuleTransfer $aclEntityRuleTransfer) use ($scope) {
                return $aclEntityRuleTransfer->getScopeOrFail() === $scope;
            },
        );

        return (new AclEntityRuleCollectionTransfer())
            ->setAclEntityRules(new ArrayObject($filteredAclEntityRuleTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByEntityClass(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $entityClass
    ): AclEntityRuleCollectionTransfer {
        $filteredAclEntityRuleTransfers = array_filter(
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
            function (AclEntityRuleTransfer $aclEntityRuleTransfer) use ($entityClass) {
                return $aclEntityRuleTransfer->getEntityOrFail() === $entityClass
                || ($aclEntityRuleTransfer->getEntityOrFail() === AclEntityConstants::WHILDCARD_ENTITY
                    && $aclEntityRuleTransfer->getScopeOrFail() === AclEntityConstants::SCOPE_GLOBAL
                    );
            },
        );

        return (new AclEntityRuleCollectionTransfer())
            ->setAclEntityRules(new ArrayObject($filteredAclEntityRuleTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $scope
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByScopeAndEntityClass(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $scope,
        string $entityClass
    ): AclEntityRuleCollectionTransfer {
        $filteredAclEntityRuleTransfers = array_filter(
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
            function (AclEntityRuleTransfer $aclEntityRuleTransfer) use ($scope, $entityClass) {
                return $aclEntityRuleTransfer->getScopeOrFail() === $scope
                    && $aclEntityRuleTransfer->getEntityOrFail() === $entityClass;
            },
        );

        return (new AclEntityRuleCollectionTransfer())
            ->setAclEntityRules(new ArrayObject($filteredAclEntityRuleTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $scope
     * @param string $entityClass
     * @param int $permissionMask
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByScopeEntityClassAndPermissionMask(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $scope,
        string $entityClass,
        int $permissionMask
    ): AclEntityRuleCollectionTransfer {
        $filteredAclEntityRuleTransfers = array_filter(
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
            function (AclEntityRuleTransfer $aclEntityRuleTransfer) use ($scope, $entityClass, $permissionMask) {
                return $aclEntityRuleTransfer->getScopeOrFail() === $scope
                    && ($aclEntityRuleTransfer->getEntityOrFail() === $entityClass || $aclEntityRuleTransfer->getEntityOrFail() === AclEntityConstants::WHILDCARD_ENTITY)
                    && ($aclEntityRuleTransfer->getPermissionMaskOrFail() & $permissionMask);
            },
        );

        return (new AclEntityRuleCollectionTransfer())
            ->setAclEntityRules(new ArrayObject($filteredAclEntityRuleTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $entityClass
     * @param int $permissionMask
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByEntityClassAndPermissionMask(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $entityClass,
        int $permissionMask
    ): AclEntityRuleCollectionTransfer {
        $filteredAclEntityRuleTransfers = array_filter(
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
            function (AclEntityRuleTransfer $aclEntityRuleTransfer) use ($entityClass, $permissionMask) {
                return $aclEntityRuleTransfer->getEntityOrFail() === $entityClass
                    && ($aclEntityRuleTransfer->getPermissionMaskOrFail() & $permissionMask);
            },
        );

        return (new AclEntityRuleCollectionTransfer())
            ->setAclEntityRules(new ArrayObject($filteredAclEntityRuleTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $permissionMask
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByPermissionMask(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $permissionMask
    ): AclEntityRuleCollectionTransfer {
        $filteredAclEntityRuleTransfers = array_filter(
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
            function (AclEntityRuleTransfer $aclEntityRuleTransfer) use ($permissionMask): bool {
                return ($aclEntityRuleTransfer->getPermissionMaskOrFail() & $permissionMask) > 0;
            },
        );

        return (new AclEntityRuleCollectionTransfer())
            ->setAclEntityRules(new ArrayObject($filteredAclEntityRuleTransfers));
    }
}
