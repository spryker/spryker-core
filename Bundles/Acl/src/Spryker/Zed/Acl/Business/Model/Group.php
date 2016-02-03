<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\GroupsTransfer;
use Spryker\Zed\Acl\Business\Exception\UserAndGroupNotFoundException;
use Orm\Zed\Acl\Persistence\SpyAclGroup;
use Orm\Zed\Acl\Persistence\SpyAclGroupsHasRoles;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroup;
use Spryker\Zed\Library\Copy;
use Spryker\Zed\Acl\Business\Exception\EmptyEntityException;
use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Spryker\Zed\Acl\Business\Exception\GroupNameExistsException;
use Spryker\Zed\Acl\Business\Exception\GroupNotFoundException;
use Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException;

class Group implements GroupInterface
{

    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainer $queryContainer
     */
    public function __construct(AclQueryContainer $queryContainer)
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
        $transfer = Copy::entityToTransfer($transfer, $entity);

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
     * @deprecated Since 0.19.0, to be removed in 1.0.0, use getUserGroups() instead.
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getUserGroup($idUser)
    {
        trigger_error('Deprecated, use getUserGroups() instead.', E_USER_DEPRECATED);

        $groupEntity = $this->queryContainer->queryUserGroupByIdUser($idUser)->findOne();

        $groupTransfer = new GroupTransfer();
        $groupTransfer->fromArray($groupEntity->toArray(), true);

        return $groupTransfer;
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
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasUserException
     *
     * @return int
     */
    public function addUser($idGroup, $idUser)
    {
        if ($this->hasUser($idGroup, $idUser)) {
            return;
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
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getAllGroups()
    {
        $collection = new GroupsTransfer();

        $results = $this->queryContainer
            ->queryGroup()
            ->find();

        foreach ($results as $result) {
            $transfer = new GroupTransfer();
            $collection->addGroup(Copy::entityToTransfer($transfer, $result));
        }

        return $collection;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getByName($name)
    {
        $entity = $this->queryContainer->queryGroupByName($name)->findOne();

        $transfer = new GroupTransfer();

        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroupById($id)
    {
        $entity = $this->getGroupEntityById($id);

        $transfer = new GroupTransfer();

        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
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
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoles($idGroup)
    {
        $results = $this->queryContainer
            ->queryGroupRoles($idGroup)
            ->find();

        $collection = new RolesTransfer();

        foreach ($results as $result) {
            $transfer = new RoleTransfer();
            Copy::entityToTransfer($transfer, $result);
            $collection->addRole($transfer);
        }

        return $collection;
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
