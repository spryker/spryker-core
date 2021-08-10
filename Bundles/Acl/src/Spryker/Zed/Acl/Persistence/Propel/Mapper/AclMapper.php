<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroup;
use Orm\Zed\Acl\Persistence\SpyAclRole;

class AclMapper
{
    /**
     * @param \Orm\Zed\Acl\Persistence\SpyAclGroup $aclGroup
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function mapAclGroupEntityToGroupTransfer(SpyAclGroup $aclGroup, GroupTransfer $groupTransfer): GroupTransfer
    {
        $groupTransfer->fromArray($aclGroup->toArray(), true);

        return $groupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Orm\Zed\Acl\Persistence\SpyAclGroup $aclGroupEntity
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroup
     */
    public function mapAclGroupTransferToGroupEntity(GroupTransfer $groupTransfer, SpyAclGroup $aclGroupEntity): SpyAclGroup
    {
        $aclGroupEntity->fromArray($groupTransfer->toArray());

        return $aclGroupEntity;
    }

    /**
     * @param \Orm\Zed\Acl\Persistence\SpyAclRole $aclRoleEntity
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function mapAclRoleEntityToRoleTransfer(SpyAclRole $aclRoleEntity, RoleTransfer $roleTransfer): RoleTransfer
    {
        $roleTransfer->fromArray($aclRoleEntity->toArray(), true);

        return $roleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     * @param \Orm\Zed\Acl\Persistence\SpyAclRole $aclRoleEntity
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRole
     */
    public function mapAclRoleTransferToRoleEntity(RoleTransfer $roleTransfer, SpyAclRole $aclRoleEntity): SpyAclRole
    {
        $aclRoleEntity->fromArray($roleTransfer->toArray());

        return $aclRoleEntity;
    }
}
