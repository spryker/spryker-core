<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use SprykerFeature\Zed\Acl\Business\Exception\GroupAlreadyHasRoleException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNameExistsException;

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
     * @throws GroupNameExistsException
     * @throws GroupNotFoundException
     */
    public function save(GroupTransfer $group);

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
     * @param int $idGroup
     * @param int $idRole
     *
     * @return int
     * @throws GroupAlreadyHasRoleException
     */
    public function addRole($idGroup, $idRole);

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
