<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\GroupBuilder;
use Generated\Shared\DataBuilder\RoleBuilder;
use Generated\Shared\Transfer\AclRoleCriteriaTransfer;
use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroup;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AclHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function haveGroup(array $seedData = []): GroupTransfer
    {
        /** @var \Generated\Shared\Transfer\GroupTransfer $groupTransfer */
        $groupTransfer = (new GroupBuilder($seedData))->build();
        $aclGroupEntity = $this->createAclGroupEntity();
        $aclGroupEntity->fromArray($groupTransfer->toArray());

        $aclGroupEntity->save();

        $groupTransfer->fromArray($aclGroupEntity->toArray(), true);

        return $groupTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function haveRole(array $seedData = []): RoleTransfer
    {
        /** @var \Generated\Shared\Transfer\RoleTransfer $roleTransfer */
        $roleTransfer = (new RoleBuilder($seedData))->build();

        $roleTransfer = $this->getLocator()
            ->acl()
            ->facade()
            ->updateRole($roleTransfer); // update method of facade works for create new records as well

        return $roleTransfer;
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup(int $idUser, int $idGroup): int
    {
        return $this->getLocator()
            ->acl()
            ->facade()
            ->addUserToGroup($idUser, $idGroup);
    }

    /**
     * @param \Generated\Shared\Transfer\AclRoleCriteriaTransfer $aclRoleCriteriaTransfer
     *
     * @return void
     */
    public function deleteRoles(AclRoleCriteriaTransfer $aclRoleCriteriaTransfer): void
    {
        $aclRoleQuery = $this->getAclRoleQuery();
        if ($aclRoleCriteriaTransfer->getNames()) {
            $aclRoleQuery->filterByName_In($aclRoleCriteriaTransfer->getNames());
        }
        if ($aclRoleCriteriaTransfer->getName()) {
            $aclRoleQuery->filterByName($aclRoleCriteriaTransfer->getName());
        }

        $aclRoleQuery = $this->filterAclRoleByReference($aclRoleCriteriaTransfer, $aclRoleQuery);

        $aclRoleQuery->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaTransfer $groupCriteriaTransfer
     *
     * @return void
     */
    public function deleteGroups(GroupCriteriaTransfer $groupCriteriaTransfer): void
    {
        $aclGroupQuery = $this->getAclGroupQuery();
        if ($groupCriteriaTransfer->getNames()) {
            $aclGroupQuery->filterByName_In($groupCriteriaTransfer->getNames());
        }
        if ($groupCriteriaTransfer->getName()) {
            $aclGroupQuery->filterByName($groupCriteriaTransfer->getName());
        }

        if ($groupCriteriaTransfer->getReference()) {
            $aclGroupQuery->filterByReference($groupCriteriaTransfer->getReference());
        }

        $aclGroupQuery->delete();
    }

    /**
     * @return void
     */
    public function ensureAclRoleTableIsEmpty(): void
    {
        SpyAclRoleQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureAclGroupTableIsEmpty(): void
    {
        SpyAclGroupQuery::create()->deleteAll();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroup
     */
    private function createAclGroupEntity(): SpyAclGroup
    {
        return new SpyAclGroup();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    protected function getAclRoleQuery(): SpyAclRoleQuery
    {
        return SpyAclRoleQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    protected function getAclGroupQuery(): SpyAclGroupQuery
    {
        return SpyAclGroupQuery::create();
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @param \Generated\Shared\Transfer\AclRoleCriteriaTransfer $aclRoleCriteriaTransfer
     * @param \Orm\Zed\Acl\Persistence\SpyAclRoleQuery $aclRoleQuery
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    protected function filterAclRoleByReference(
        AclRoleCriteriaTransfer $aclRoleCriteriaTransfer,
        SpyAclRoleQuery $aclRoleQuery
    ): SpyAclRoleQuery {
        if ($aclRoleCriteriaTransfer->getReference()) {
            $aclRoleQuery->filterByReference($aclRoleCriteriaTransfer->getReference());
        }

        return $aclRoleQuery;
    }
}
