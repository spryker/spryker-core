<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory getFactory()
 */
class AclEntityRepository extends AbstractRepository implements AclEntityRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRulesByRoles(RolesTransfer $rolesTransfer): AclEntityRuleCollectionTransfer
    {
        $roleIds = $this->getRoleIdsFromRolesTransfer($rolesTransfer);

        $aclEntityRuleEntities = $this->getFactory()
            ->createAclEntityRuleQuery()
            ->filterByFkAclRole_In($roleIds)
            ->find();

        return $this->getFactory()
            ->createAclEntityRuleMapper()
            ->mapAclEntityRuleCollectionToAclEntityRuleCollectionTransfer(
                $aclEntityRuleEntities,
                new AclEntityRuleCollectionTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return int[]
     */
    protected function getRoleIdsFromRolesTransfer(RolesTransfer $rolesTransfer): array
    {
        return array_map(
            function (RoleTransfer $roleTransfer): int {
                return $roleTransfer->getIdAclRoleOrFail();
            },
            $rolesTransfer->getRoles()->getArrayCopy()
        );
    }
}
