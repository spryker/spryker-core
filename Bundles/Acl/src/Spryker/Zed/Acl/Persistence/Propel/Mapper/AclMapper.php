<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer;
use Generated\Shared\Transfer\AclUserHasGroupTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroup;
use Orm\Zed\Acl\Persistence\SpyAclRole;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroup;
use Propel\Runtime\Collection\ObjectCollection;

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

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Acl\Persistence\SpyAclUserHasGroup> $aclUserHasGroupEntities
     * @param \Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer $aclUserHasGroupCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer
     */
    public function mapAclUserHasGroupEntitiesToAclUserHasGroupCollectionTransfer(
        ObjectCollection $aclUserHasGroupEntities,
        AclUserHasGroupCollectionTransfer $aclUserHasGroupCollectionTransfer
    ): AclUserHasGroupCollectionTransfer {
        foreach ($aclUserHasGroupEntities as $aclUserHasGroupEntity) {
            $aclUserHasGroupCollectionTransfer->addAclUserHasGroup(
                $this->mapAclUserHasGroupEntityToAclUserHasGroupTransfer(
                    $aclUserHasGroupEntity,
                    new AclUserHasGroupTransfer(),
                ),
            );
        }

        return $aclUserHasGroupCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Acl\Persistence\SpyAclUserHasGroup $aclUserHasGroupEntity
     * @param \Generated\Shared\Transfer\AclUserHasGroupTransfer $aclUserHasGroupTransfer
     *
     * @return \Generated\Shared\Transfer\AclUserHasGroupTransfer
     */
    protected function mapAclUserHasGroupEntityToAclUserHasGroupTransfer(
        SpyAclUserHasGroup $aclUserHasGroupEntity,
        AclUserHasGroupTransfer $aclUserHasGroupTransfer
    ): AclUserHasGroupTransfer {
        $aclUserHasGroupTransfer->setUser((new UserTransfer())->setIdUser($aclUserHasGroupEntity->getFkUser()))
            ->setGroup((new GroupTransfer())->setIdAclGroup($aclUserHasGroupEntity->getFkAclGroup()));

        return $aclUserHasGroupTransfer;
    }
}
