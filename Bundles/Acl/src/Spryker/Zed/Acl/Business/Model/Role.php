<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Acl\Persistence\SpyAclRole;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Zed\Acl\Business\Exception\EmptyEntityException;
use Spryker\Zed\Acl\Business\Exception\RoleNameEmptyException;
use Spryker\Zed\Acl\Business\Exception\RoleNameExistsException;
use Spryker\Zed\Acl\Business\Exception\RoleNotFoundException;
use Spryker\Zed\Acl\Business\Exception\RootNodeModificationException;
use Spryker\Zed\Acl\Persistence\AclQueryContainerInterface;

class Role implements RoleInterface
{
    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Acl\Business\Model\GroupInterface
     */
    protected $group;

    /**
     * @param \Spryker\Zed\Acl\Business\Model\GroupInterface $group
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface $queryContainer
     */
    public function __construct(GroupInterface $group, AclQueryContainerInterface $queryContainer)
    {
        $this->group = $group;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function addRole($name)
    {
        $data = new RoleTransfer();
        $data->setName($name);

        $role = $this->save($data);

        return $role;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNameExistsException
     * @throws \Spryker\Zed\Acl\Business\Exception\RootNodeModificationException
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNameEmptyException
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function save(RoleTransfer $roleTransfer)
    {
        $aclRoleEntity = new SpyAclRole();
        if ($roleTransfer->getIdAclRole()) {
            $aclRoleEntity = $this->queryContainer->queryRoleById($roleTransfer->getIdAclRole())->findOne();
            if ($aclRoleEntity->getName() === AclConstants::ROOT_ROLE) {
                throw new RootNodeModificationException('Could not modify root role node!');
            }
        }

        if (!$roleTransfer->getName()) {
            throw new RoleNameEmptyException(
                sprintf('Role name should not be empty!')
            );
        }

        if ($aclRoleEntity->getName() !== $roleTransfer->getName() && $this->hasRoleName($roleTransfer->getName())) {
            throw new RoleNameExistsException(
                sprintf('Role with name "%s" already exists!', $roleTransfer->getName())
            );
        }

        $aclRoleEntity->setName($roleTransfer->getName());
        $aclRoleEntity->save();

        $roleTransfer = new RoleTransfer();
        $roleTransfer->fromArray($aclRoleEntity->toArray(), true);

        return $roleTransfer;
    }

    /**
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRoleId($idRole)
    {
        $entity = $this->queryContainer->queryRoleById($idRole)->count();

        return $entity > 0;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasRoleName($name)
    {
        $aclRoleEntity = $this->queryContainer->queryRoleByName($name)->count();

        return $aclRoleEntity > 0;
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getUserRoles($idUser)
    {
        $groupsTransfer = $this->group->getUserGroups($idUser);

        $rolesTransfer = new RolesTransfer();
        foreach ($groupsTransfer->getGroups() as $groupTransfer) {
            $this->addGroupRoles($rolesTransfer, $groupTransfer->getIdAclGroup());
        }

        return $rolesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     * @param int $idAclGroup
     *
     * @return void
     */
    protected function addGroupRoles(RolesTransfer $rolesTransfer, $idAclGroup)
    {
        $groupRoles = $this->getGroupRoles($idAclGroup);

        foreach ($groupRoles->getRoles() as $groupRole) {
            $rolesTransfer->addRole($groupRole);
        }
    }

    /**
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getGroupRoles($idGroup)
    {
        $aclRoleEntities = $this->queryContainer->queryGroupRoles($idGroup)->find();

        $rolesTransfer = new RolesTransfer();

        foreach ($aclRoleEntities as $aclRoleEntity) {
            $roleTransfer = new RoleTransfer();
            $roleTransfer->fromArray($aclRoleEntity->toArray(), true);

            $rolesTransfer->addRole($roleTransfer);
        }

        return $rolesTransfer;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\EmptyEntityException
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleById($id)
    {
        $aclRoleEntity = $this->queryContainer->queryRoleById($id)->findOne();

        if ($aclRoleEntity === null) {
            throw new EmptyEntityException();
        }

        $roleTransfer = new RoleTransfer();
        $roleTransfer->fromArray($aclRoleEntity->toArray(), true);

        return $roleTransfer;
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer|null
     */
    public function findRoleById(int $id): ?RoleTransfer
    {
        $aclRoleEntity = $this->queryContainer->queryRoleById($id)->findOne();

        if ($aclRoleEntity === null) {
            return null;
        }

        $roleTransfer = new RoleTransfer();
        $roleTransfer->fromArray($aclRoleEntity->toArray(), true);

        return $roleTransfer;
    }

    /**
     * @param int $idRole
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNotFoundException
     *
     * @return bool
     */
    public function removeRoleById($idRole)
    {
        $aclRules = $this->queryContainer->queryRuleByRoleId($idRole)->find();
        $aclRules->delete();

        $aclRoleEntity = $this->queryContainer->queryRoleById($idRole)->delete();

        if ($aclRoleEntity <= 0) {
            throw new RoleNotFoundException();
        }

        return true;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getByName($name)
    {
        $aclRoleEntity = $this->queryContainer->queryRoleByName($name)->findOne();

        $roleTransfer = new RoleTransfer();
        $roleTransfer->fromArray($aclRoleEntity->toArray(), true);

        return $roleTransfer;
    }
}
