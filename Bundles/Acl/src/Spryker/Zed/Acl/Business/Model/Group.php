<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupsTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroup;
use Orm\Zed\Acl\Persistence\SpyAclGroupsHasRoles;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroup;
use Spryker\Zed\Acl\Business\Exception\EmptyEntityException;
use Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException;
use Spryker\Zed\Acl\Business\Exception\GroupNameExistsException;
use Spryker\Zed\Acl\Business\Exception\GroupNotFoundException;
use Spryker\Zed\Acl\Business\Exception\UserAndGroupNotFoundException;
use Spryker\Zed\Acl\Persistence\AclQueryContainerInterface;

class Group implements GroupInterface
{
    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface $queryContainer
     */
    public function __construct(AclQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function addGroup($name)
    {
        $data = new GroupTransfer();
        $data->setName($name);
        $this->assertGroupHasName($data);

        return $this->save($data);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $group)
    {
        $original = $this->getGroupById($group->getIdAclGroup());

        if ($group->getName() !== $original->getName()) {
            $this->assertGroupHasName($group);
        }

        return $this->save($group);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function save(GroupTransfer $group)
    {
        $this->assertGroupExists($group);

        if ($group->getIdAclGroup() !== null) {
            $entity = $this->getEntityGroupById($group->getIdAclGroup());
        } else {
            $entity = new SpyAclGroup();
        }

        $entity->setName($group->getName());
        $entity->save();

        $transfer = new GroupTransfer();
        $transfer->fromArray($entity->toArray(), true);

        return $transfer;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroup
     */
    public function getEntityGroupById($id)
    {
        $entity = $this->queryContainer->queryGroupById($id)->findOne();

        if ($entity === null) {
            throw new GroupNotFoundException();
        }

        return $entity;
    }

    /**
     * @param int $idGroup
     *
     * @return bool
     */
    public function hasGroup($idGroup)
    {
        $amount = $this->queryContainer->queryGroupById($idGroup)->count();

        return $amount > 0;
    }

    /**
     * @param int $name
     *
     * @return bool
     */
    public function hasGroupName($name)
    {
        $amount = $this->queryContainer->queryGroupByName($name)->count();

        return $amount > 0;
    }

    /**
     * @param int $idGroup
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRole($idGroup, $idRole)
    {
        $amount = $this->queryContainer->queryGroupHasRoleById($idGroup, $idRole)->count();

        return $amount > 0;
    }

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return bool
     */
    public function hasUser($idGroup, $idUser)
    {
        $amount = $this->queryContainer->queryUserHasGroupById($idGroup, $idUser)->count();

        return $amount > 0;
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser)
    {
        $groupEntities = $this->queryContainer->queryUserGroupByIdUser($idUser)->find();

        $groupsTransfer = new GroupsTransfer();

        foreach ($groupEntities as $groupEntity) {
            $groupTransfer = new GroupTransfer();
            $groupTransfer->fromArray($groupEntity->toArray(), true);

            $groupsTransfer->addGroup($groupTransfer);
        }

        return $groupsTransfer;
    }

    /**
     * @param int $idRole
     * @param int $idGroup
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup)
    {
        if ($this->hasRole($idGroup, $idRole)) {
            throw new GroupAlreadyHasRoleException();
        }

        $entity = new SpyAclGroupsHasRoles();

        $entity->setFkAclGroup($idGroup)
            ->setFkAclRole($idRole);

        return $entity->save();
    }

    /**
     * @param int $idAclGroup
     *
     * @return void
     */
    public function removeRolesFromGroup($idAclGroup)
    {
        $groupRoles = $this->queryContainer->queryGroupHasRole($idAclGroup)->find();

        foreach ($groupRoles as $role) {
            $role->delete();
        }
    }

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return int
     */
    public function addUser($idGroup, $idUser)
    {
        if ($this->hasUser($idGroup, $idUser)) {
            return 0;
        }

        $entity = new SpyAclUserHasGroup();

        $entity->setFkAclGroup($idGroup)
            ->setFkUser($idUser);

        return $entity->save();
    }

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\UserAndGroupNotFoundException
     *
     * @return void
     */
    public function removeUser($idGroup, $idUser)
    {
        $entity = $this->queryContainer
            ->queryUserHasGroupById($idGroup, $idUser)
            ->findOne();

        if (!$entity) {
            throw new UserAndGroupNotFoundException();
        }

        $entity->delete();
    }

    /**
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups()
    {
        $groupTransferCollection = new GroupsTransfer();

        $groupCollection = $this->queryContainer
            ->queryGroup()
            ->find();

        foreach ($groupCollection as $groupEntity) {
            $groupTransfer = new GroupTransfer();
            $groupTransfer->fromArray($groupEntity->toArray(), true);

            $groupTransferCollection->addGroup($groupTransfer);
        }

        return $groupTransferCollection;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getByName($name)
    {
        $groupEntity = $this->queryContainer->queryGroupByName($name)->findOne();

        $groupTransfer = new GroupTransfer();
        $groupTransfer->fromArray($groupEntity->toArray(), true);

        return $groupTransfer;
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroupById($id)
    {
        $groupEntity = $this->getGroupEntityById($id);

        $groupTransfer = new GroupTransfer();
        $groupTransfer->fromArray($groupEntity->toArray(), true);

        return $groupTransfer;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return bool
     */
    public function removeGroupById($id)
    {
        $entity = $this->queryContainer
            ->queryGroupById($id)
            ->delete();

        if ($entity <= 0) {
            throw new GroupNotFoundException();
        }

        return true;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\EmptyEntityException
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroup
     */
    protected function getGroupEntityById($id)
    {
        $entity = $this->queryContainer->queryGroupById($id)->findOne();

        if ($entity === null) {
            throw new EmptyEntityException();
        }

        return $entity;
    }

    /**
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getRoles($idGroup)
    {
        $roleCollection = $this->queryContainer
            ->queryGroupRoles($idGroup)
            ->find();

        $roleTransferCollection = new RolesTransfer();

        foreach ($roleCollection as $roleEntity) {
            $roleTransfer = new RoleTransfer();
            $roleTransfer->fromArray($roleEntity->toArray(), true);
            $roleTransferCollection->addRole($roleTransfer);
        }

        return $roleTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNameExistsException
     *
     * @return void
     */
    public function assertGroupHasName(GroupTransfer $group)
    {
        if ($this->hasGroupName($group->getName()) === true) {
            throw new GroupNameExistsException();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return void
     */
    public function assertGroupExists(GroupTransfer $group)
    {
        if ($group->getIdAclGroup() !== null && $this->hasGroup($group->getIdAclGroup()) === false) {
            throw new GroupNotFoundException();
        }
    }
}
