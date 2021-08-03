<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Filter;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;

interface AclEntityRuleCollectionTransferFilterInterface
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
    ): AclEntityRuleCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $entityClass
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByEntityClass(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $entityClass
    ): AclEntityRuleCollectionTransfer;

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
    ): AclEntityRuleCollectionTransfer;

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
    ): AclEntityRuleCollectionTransfer;

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
    ): AclEntityRuleCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param int $permissionMask
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function filterByPermissionMask(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        int $permissionMask
    ): AclEntityRuleCollectionTransfer;
}
