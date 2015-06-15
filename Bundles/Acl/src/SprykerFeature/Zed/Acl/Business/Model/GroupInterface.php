<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use SprykerFeature\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNameExistsException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroup;

interface GroupInterface
{

    /**
     * @param string $name
     *
     * @return GroupTransfer
     */
    public function addGroup($name);

    /**
     * @param GroupTransfer $group
     *
     * @return GroupTransfer
     */
    public function updateGroup(GroupTransfer $group);

    /**
     * @param GroupTransfer $group
     *
     * @return GroupTransfer
     * @throws GroupNameExistsException
     * @throws GroupNotFoundException
     */
    public function save(GroupTransfer $group);

    /**
     * @param int $id
     *
     * @return SpyAclGroup
     * @throws GroupNotFoundException
     */
    public function getEntityGroupById($id);

    /**
     * @param int $idGroup
     * @param int $idUser
     */
    public function removeUser($idGroup, $idUser);

    /**
     * @return GroupTransfer
     */
    public function getAllGroups();

    /**
     * @param GroupTransfer $group
     *
     * @throws GroupNameExistsException
     */
    public function assertGroupHasName(GroupTransfer $group);

    /**
     * @param GroupTransfer $group
     *
     * @throws GroupNotFoundException
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
     * @param int $idUser
     *
     * @return GroupTransfer
     */
    public function getUserGroup($idUser);

    /**
     * @param int $idRole
     * @param int $idGroup
     *
     * @return int
     * @throws GroupAlreadyHasRoleException
     */
    public function addRoleToGroup($idRole, $idGroup);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return int
     * @throws GroupAlreadyHasRoleException
     */
    public function addUser($idGroup, $idUser);

    /**
     * @param string $name
     *
     * @return GroupTransfer
     */
    public function getByName($name);

    /**
     * @param int $id
     *
     * @return GroupTransfer
     * @throws GroupNotFoundException
     */
    public function getGroupById($id);

    /**
     * @param int $id
     *
     * @return bool
     * @throws GroupNotFoundException
     */
    public function removeGroupById($id);

    /**
     * @param int $idGroup
     *
     * @return RoleTransfer
     * @throws GroupNotFoundException
     */
    public function getRoles($idGroup);
}
