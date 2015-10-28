<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupsTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use SprykerFeature\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNameExistsException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use Orm\Zed\Acl\Persistence\SpyAclGroup;

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
     * @throws GroupNameExistsException
     * @throws GroupNotFoundException
     *
     * @return GroupTransfer
     */
    public function save(GroupTransfer $group);

    /**
     * @param int $id
     *
     * @throws GroupNotFoundException
     *
     * @return SpyAclGroup
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
     * @param integer $idUser
     *
     * @return GroupsTransfer
     */
    public function getUserGroups($idUser);

    /**
     * @param int $idRole
     * @param int $idGroup
     *
     * @throws GroupAlreadyHasRoleException
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @throws GroupAlreadyHasRoleException
     *
     * @return int
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
     * @throws GroupNotFoundException
     *
     * @return GroupTransfer
     */
    public function getGroupById($id);

    /**
     * @param int $id
     *
     * @throws GroupNotFoundException
     *
     * @return bool
     */
    public function removeGroupById($id);

    /**
     * @param int $idGroup
     *
     * @throws GroupNotFoundException
     *
     * @return RoleTransfer
     */
    public function getRoles($idGroup);

}
