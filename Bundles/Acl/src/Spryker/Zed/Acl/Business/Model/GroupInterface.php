<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupTransfer;

interface GroupInterface
{

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function addGroup($name);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $group);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNameExistsException
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function save(GroupTransfer $group);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroup
     */
    public function getEntityGroupById($id);

    /**
     * @param int $idGroup
     * @param int $idUser
     */
    public function removeUser($idGroup, $idUser);

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getAllGroups();

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNameExistsException
     */
    public function assertGroupHasName(GroupTransfer $group);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $group
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     */
    public function assertGroupExists(GroupTransfer $group);

    /**
     * @param int $idGroup
     *
     * @return bool
     */
    public function hasGroup($idGroup);

    /**
     * @param int $name
     *
     * @return bool
     */
    public function hasGroupName($name);

    /**
     * @param int $idGroup
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRole($idGroup, $idRole);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return bool
     */
    public function hasUser($idGroup, $idUser);

    /**
     * @deprecated since 0.19.0 to be removed in 1.0.0
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getUserGroup($idUser);

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser);

    /**
     * @param int $idRole
     * @param int $idGroup
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException
     *
     * @return int
     */
    public function addUser($idGroup, $idUser);

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getByName($name);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroupById($id);

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return bool
     */
    public function removeGroupById($id);

    /**
     * @param int $idGroup
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoles($idGroup);

}
